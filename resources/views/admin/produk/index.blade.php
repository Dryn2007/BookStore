@extends('layouts.admin')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:wght@300;400;600&display=swap"
        rel="stylesheet">

    <div class="page-container">
        <!-- Header Section -->
        <div class="page-header">
            <h1 class="page-title">🛒 Product Management</h1>
            <p class="page-subtitle">Manage your product inventory with ease</p>
            <div class="stat-box">
                <span class="stat-number">{{ $produks->count() }}</span>
                <span class="stat-label">Total Products</span>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="breadcrumb-nav">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">🏠 Dashboard</a>
                <span class="breadcrumb-separator">→</span>
                <span class="breadcrumb-item active">Products</span>
            </div>
            <a href="{{ route('admin.produk.create') }}" class="btn">➕ Add New Product</a>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <input type="text" id="searchInput" class="search-input" placeholder="Search products...">
            <select id="sortBy" class="filter-select">
                <option value="name">Sort by Name</option>
                <option value="price">Sort by Price</option>
            </select>
        </div>

        <!-- Products Grid -->
        <div class="products-container" id="productsContainer">
            @forelse ($produks as $index => $produk)
                <div class="product-card" data-name="{{ strtolower($produk->nama) }}" data-price="{{ $produk->harga }}">
                    @if ($produk->foto)
                        <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="product-image">
                    @else
                        <div class="image-placeholder">📦 No Image</div>
                    @endif

                    <h3 class="product-name">{{ $produk->nama }}</h3>
                    <p class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    <div class="stock-info">
                        <span class="stock-label">Stock:</span>
                        <span class="stock-value {{ $produk->stock < 5 ? 'low-stock' : '' }}">
                            {{ $produk->stock }}
                        </span>
                    </div>
                    <form action="{{ route('admin.produk.updateStock', $produk->id) }}" method="POST" class="stock-form">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="stock" value="{{ $produk->stock }}" min="0">
                        <button type="submit" class="btn btn-primary">Update Stock</button>
                    </form>

                    <div class="product-actions">
                        <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn-action">✏️ Edit</a>
                        <button type="button" class="btn-action delete" onclick="confirmDelete({{ $produk->id }})">🗑️
                            Delete</button>
                    </div>

                    <form id="delete-form-{{ $produk->id }}" action="{{ route('admin.produk.destroy', $produk->id) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            @empty
                <div class="empty-state">
                    <h3>No Products Found</h3>
                    <p>Start by adding your first product to the inventory</p>
                </div>
            @endforelse
        </div>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5D4037',
                cancelButtonColor: '#A1887F',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        // Search filter
        document.getElementById("searchInput").addEventListener("keyup", function () {
            let value = this.value.toLowerCase();
            let products = document.querySelectorAll(".product-card");

            products.forEach(card => {
                let name = card.getAttribute("data-name");
                if (name.includes(value)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });

        document.querySelectorAll('.stock-form').forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (!response.ok) throw new Error('Failed to update stock');

                        window.location.reload();
                    } catch (error) {
                        alert(error.message);
                    }
                });
            });
    </script>

    <script>
        // Add this to your existing JavaScript
        document.querySelectorAll('.stock-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;

                submitButton.innerHTML = 'Updating...';
                submitButton.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Stock Updated',
                            text: 'Product stock has been updated successfully',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Update the displayed stock value
                        const stockValue = this.closest('.product-card').querySelector('.stock-value');
                        stockValue.textContent = formData.get('stock');
                        stockValue.classList.toggle('low-stock', formData.get('stock') < 5);
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update stock',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                    });
            });
        });
    </script>

    <style>
        :root {
            --primary-color: #5D4037;
            --secondary-color: #A1887F;
            --background-color: #F5F5F5;
        }

        body,
        .page-container {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
        }

        .page-container {
            padding: 2rem;
        }
        h1,
        h3 {
            font-family: 'Lora', serif;
            color: var(--primary-color);
        }

        .page-header {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .page-title {
            font-size: 2rem;
            margin: 0;
        }

        .page-subtitle {
            color: var(--secondary-color);
        }

        .stat-box {
            margin-top: 1rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .breadcrumb-item {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .breadcrumb-item.active {
            font-weight: bold;
            color: var(--primary-color);
        }

        .btn {
            background: var(--primary-color);
            color: #fff;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn:hover {
            background: var(--secondary-color);
        }

        .filter-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .search-input,
        .filter-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            flex: 1;
        }

        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .product-card {
            background: #fff;
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .image-placeholder {
            background: #eee;
            height: 180px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #999;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .product-actions {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            background: var(--secondary-color);
            color: #fff;
            text-decoration: none;
        }

        .btn-action.delete {
            background: #d32f2f;
        }

        .btn-action:hover {
            opacity: 0.9;
        }

        .empty-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 5rem;
            background: #fff;
            border-radius: 12px;
        }
    </style>
@endsection