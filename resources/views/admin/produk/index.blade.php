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
                    <div class="product-image-container">
                        @if ($produk->foto)
                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="product-image">
                        @else
                            <div class="image-placeholder">📦</div>
                        @endif
                    </div>

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
                        <button type="submit" class="btn-update-stock">Update</button>
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

        // Stock Update Form Handler
        document.querySelectorAll('.stock-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;

                // Disable button and show loading state
                submitButton.innerHTML = '⏳';
                submitButton.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => {
                        if (response.ok) {
                            // Show success notification
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Stok produk berhasil diupdate',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true
                            }).then(() => {
                                // Reload page after notification
                                window.location.reload();
                            });
                        } else {
                            throw new Error('Failed to update stock');
                        }
                    })
                    .catch(error => {
                        // Show error notification
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengupdate stok produk',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        console.error('Error:', error);

                        // Restore button state on error
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
            --card-background: #FFFFFF;
            --font-heading: 'Lora', serif;
            --font-body: 'Poppins', sans-serif;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --danger-color: #d32f2f;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--background-color);
            color: var(--primary-color);
        }

        .page-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        h1,
        h3 {
            font-family: var(--font-heading);
            color: var(--primary-color);
        }

        /* Header Section - Enhanced */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 8px 24px rgba(93, 64, 55, 0.15);
            color: white;
        }

        .page-title {
            font-size: 2.2rem;
            margin: 0;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        .stat-box {
            margin-top: 1.5rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 1rem 2rem;
            border-radius: 12px;
            display: inline-block;
            backdrop-filter: blur(10px);
        }

        .stat-number {
            display: block;
            font-size: 2rem;
            font-weight: bold;
            color: white;
        }

        .stat-label {
            display: block;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 0.3rem;
        }

        /* Action Bar - Enhanced */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--card-background);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .breadcrumb-item {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .breadcrumb-item:hover {
            color: var(--primary-color);
        }

        .breadcrumb-item.active {
            font-weight: 600;
            color: var(--primary-color);
        }

        .breadcrumb-separator {
            color: var(--secondary-color);
        }

        .btn {
            background: var(--primary-color);
            color: #fff;
            padding: 0.7rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(93, 64, 55, 0.3);
        }

        /* Filter Section - Enhanced */
        .filter-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            background: var(--card-background);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .search-input,
        .filter-select {
            padding: 0.8rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            flex: 1;
            font-size: 0.95rem;
            transition: all 0.3s;
            background: white;
        }

        .search-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(93, 64, 55, 0.1);
        }

        .search-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 1rem center;
            padding-left: 3rem;
        }

        /* Products Grid - Enhanced */
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: var(--card-background);
            padding: 1.5rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            border-top: 4px solid transparent;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
            border-top-color: var(--primary-color);
        }

        /* Image Container untuk handle overflow */
        .product-image-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 1rem;
            position: relative;
            background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
            display: block;
        }

        .product-card:hover .product-image {
            transform: scale(1.08);
        }

        .image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #999;
            font-size: 3rem;
        }

        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0.5rem 0;
            color: var(--primary-color);
            line-height: 1.4;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--success-color);
            margin: 0.8rem 0;
        }

        /* Stock Info - Enhanced */
        .stock-info {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem;
            background: #f8f8f8;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .stock-label {
            font-size: 0.9rem;
            color: #666;
            font-weight: 500;
        }

        .stock-value {
            font-size: 1rem;
            font-weight: bold;
            color: var(--success-color);
            padding: 0.2rem 0.8rem;
            background: rgba(76, 175, 80, 0.1);
            border-radius: 20px;
        }

        .stock-value.low-stock {
            color: var(--danger-color);
            background: rgba(211, 47, 47, 0.1);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        /* Stock Form - Enhanced */
        .stock-form {
            display: flex;
            gap: 0.5rem;
            margin: 1rem 0;
            width: 100%;
        }

        .stock-form input {
            flex: 1;
            min-width: 0;
            padding: 0.6rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .stock-form input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(93, 64, 55, 0.1);
        }

        .btn-update-stock {
            background: var(--success-color);
            color: white;
            padding: 0.6rem 0.8rem;
            font-size: 0.85rem;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
            min-width: 70px;
        }

        .btn-update-stock:hover {
            background: #45a049;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }

        /* Product Actions - Enhanced */
        .product-actions {
            margin-top: 1rem;
            display: flex;
            justify-content: center;
            gap: 0.8rem;
        }

        .btn-action {
            flex: 1;
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            background: var(--secondary-color);
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-action.delete {
            background: var(--danger-color);
        }

        .btn-action.delete:hover {
            background: #b71c1c;
        }

        /* Empty State - Enhanced */
        .empty-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 5rem 2rem;
            background: var(--card-background);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .empty-state h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .empty-state p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-container {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.6rem;
            }

            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-section {
                flex-direction: column;
            }

            .products-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection