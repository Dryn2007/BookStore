@extends('layouts.admin')

@section('content')
    <div class="transaction-dashboard">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-info">
                    <h1 class="page-title">
                        <span class="title-icon">📋</span>
                        Manajemen Pesanan
                    </h1>
                    <p class="page-subtitle">Pantau dan kelola semua pesanan user</p>
                </div>
                <div class="header-stats">
                    <div class="stat-card">
                        <div class="stat-value">{{ $transactions->where('status', 'pending')->count() }}</div>
                        <div class="stat-label">Menunggu Pembayaran</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $transactions->where('status', 'dikirim')->count() }}</div>
                        <div class="stat-label">Dikirim</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ $transactions->where('status', 'selesai')->count() }}</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="filters-section">
            <div class="filter-tabs">
                <button class="filter-tab active" data-status="all">All Transactions</button>
                <button class="filter-tab" data-status="pending">Menunggu Pembayaran</button>
                <button class="filter-tab" data-status="dikirim">Dikirim</button>
                <button class="filter-tab" data-status="selesai">Selesai</button>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Cari berdasarkan user, item, atau ID..." class="search-input">
                <button class="search-btn">🔍</button>
            </div>
        </div>

        <div class="transactions-container">
            @php
                $groupedTransactions = $transactions->groupBy('user_id');
            @endphp

            @forelse ($groupedTransactions as $userId => $userTransactions)
                <div class="user-transactions-section">

                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr($userTransactions->first()->user->name, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <h3 class="user-name">{{ $userTransactions->first()->user->name }}</h3>
                        </div>
                    </div>

                    <div class="transactions-scroll">
                        <div class="transactions-row">
                            @foreach ($userTransactions as $trx)
                                <div class="transaction-card" data-status="{{ $trx->status }}">
                                    <div class="card-header">
                                        <div class="invoice-info">
                                            <h3 class="invoice-number">#{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                            <div class="transaction-date">
                                                {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                        <div class="status-badge status-{{ $trx->status }}">
                                            @if($trx->status === 'pending')
                                                <span class="status-icon">⏳</span>
                                            @elseif($trx->status === 'dikirim')
                                                <span class="status-icon">🚚</span>
                                            @elseif($trx->status === 'selesai')
                                                <span class="status-icon">✅</span>
                                            @endif
                                            {{ strtoupper($trx->status) }}
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <p class="customer-address" style="font-size: 0.9rem; color: #555; margin-bottom: 1rem;">📍
                                            {{ Str::limit($trx->alamat, 35) }}
                                        </p>

                                        <div class="payment-info">
                                            <div class="payment-method">
                                                <span class="payment-label">Payment Method:</span>
                                                <span class="payment-value">{{ ucfirst($trx->metode_pembayaran) }}</span>
                                            </div>
                                            <div class="total-amount">
                                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <div class="products-section">
                                            <h5 class="products-title">🛒 Items Ordered</h5>
                                            <div class="products-list">
                                                @foreach ($trx->items as $item)
                                                    <div class="product-item">
                                                        <div class="product-info">
                                                            <span class="product-name">{{ $item->produk->nama }}</span>
                                                            <span class="product-quantity">x{{ $item->jumlah }}</span>
                                                        </div>
                                                        <div class="product-price">
                                                            Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-actions">
                                        @if ($trx->status === 'pending')
                                            <form method="POST" action="{{ route('admin.transactions.konfirmasi', $trx->id) }}"
                                                class="confirm-form" id="confirm-form-{{ $trx->id }}">
                                                @csrf
                                                <button type="submit" class="action-btn btn-confirm">
                                                    <span class="btn-icon">✅</span>
                                                    Confirm Order
                                                </button>
                                            </form>
                                        @endif

                                        @if ($trx->status === 'dikirim')
                                            {{-- <button class="action-btn btn-complete" onclick="markAsComplete({{ $trx->id }})">
                                                <span class="btn-icon">📦</span>
                                                Mark Complete
                                            </button> --}}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>No Transactions Found</h3>
                    <p>There are no transactions to display at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // [FIX] JavaScript diperbarui untuk layout baru
                const filterTabs = document.querySelectorAll('.filter-tab');
                const transactionCards = document.querySelectorAll('.transaction-card');
                const userSections = document.querySelectorAll('.user-transactions-section'); // Ambil section user

                // Filter functionality
                filterTabs.forEach(tab => {
                    tab.addEventListener('click', function () {
                        filterTabs.forEach(t => t.classList.remove('active'));
                        this.classList.add('active');
                        const status = this.dataset.status;

                        userSections.forEach(section => { // Loop setiap section user
                            let hasVisibleCards = false;
                            const cards = section.querySelectorAll('.transaction-card');

                            cards.forEach(card => { // Loop setiap kartu di dalam section
                                if (status === 'all' || card.dataset.status === status) {
                                    card.style.display = 'block';
                                    hasVisibleCards = true;
                                } else {
                                    card.style.display = 'none';
                                }
                            });

                            // Tampilkan/sembunyikan seluruh section user
                            if (hasVisibleCards) {
                                section.style.display = 'flex'; // Gunakan 'flex' karena ini adalah flex container
                            } else {
                                section.style.display = 'none';
                            }
                        });
                    });
                });

                // Search functionality
                const searchInput = document.querySelector('.search-input');
                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();

                    userSections.forEach(section => { // Loop setiap section user
                        const text = section.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            section.style.display = 'flex'; // Gunakan 'flex'
                        } else {
                            section.style.display = 'none';
                        }
                    });
                });

                // Search button functionality
                const searchBtn = document.querySelector('.search-btn');
                searchBtn.addEventListener('click', function () {
                    const searchTerm = searchInput.value.toLowerCase();

                    userSections.forEach(section => { // Loop setiap section user
                        const text = section.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            section.style.display = 'flex'; // Gunakan 'flex'
                        } else {
                            section.style.display = 'none';
                        }
                    });
                });

                // Confirm form submission
                const confirmForms = document.querySelectorAll('.confirm-form');
                confirmForms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        const confirmBtn = this.querySelector('.btn-confirm');
                        confirmBtn.innerHTML = '<span class="btn-icon">⏳</span>Processing...';
                        confirmBtn.disabled = true;

                        // Submit the form
                        this.submit();
                    });
                });

                // Card hover effects
                transactionCards.forEach(card => {
                    card.addEventListener('mouseenter', function () {
                        this.style.transform = 'translateY(-4px) scale(1.02)';
                    });

                    card.addEventListener('mouseleave', function () {
                        this.style.transform = 'translateY(0) scale(1)';
                    });
                });
            });

            function markAsComplete(transactionId) {
                if (confirm('Mark this transaction as completed?')) {
                    // Add your completion logic here
                    alert('Transaction marked as completed!');
                    // Anda bisa menambahkan logic AJAX di sini untuk submit form
                    // location.reload(); // Contoh: reload halaman
                }
            }
        </script>
    @endpush

    <style>
        :root {
            --primary-color: #5D4037;
            --secondary-color: #A1887F;
            --background-color: #F5F5F5;
        }

        .transaction-dashboard {
            /* Perbaikan: selector harusnya .transaction-dashboard */
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            padding: 20px;
        }


        h1,
        h3,
        h4,
        h5 {
            font-family: 'Lora', serif;
            color: var(--primary-color);
            margin: 0;
        }

        .dashboard-header {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 2rem;
            margin-bottom: .3rem;
        }

        .page-subtitle {
            color: var(--secondary-color);
            font-size: 0.95rem;
        }

        .header-stats {
            display: flex;
            gap: 1rem;
            flex-shrink: 0;
        }

        .stat-card {
            background: var(--background-color);
            padding: 1rem;
            border-radius: 10px;
            flex: 1;
            text-align: center;
            min-width: 100px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--secondary-color);
        }

        .filters-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 8px;
            background: #fff;
            color: var(--primary-color);
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .filter-tab.active,
        .filter-tab:hover {
            background: var(--primary-color);
            color: #fff;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 8px;
            padding: 0.3rem 0.6rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .search-input {
            border: none;
            outline: none;
            padding: 0.5rem;
            flex: 1;
        }

        .search-btn {
            background: var(--primary-color);
            border: none;
            color: #fff;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
        }

        /* === [START] CSS LAYOUT BARU === */

        .transactions-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .user-transactions-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin: 0;
            /* Dihapus margin 20px agar rata */

            /* [PERUBAHAN] Mengatur layout bersebelahan */
            display: flex;
            flex-direction: row;
            overflow: hidden;
            /* Penting agar border-radius berfungsi */
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;

            /* [PERUBAHAN] Menjadi sidebar kiri */
            padding: 1.5rem;
            border-right: 2px solid var(--background-color);
            width: 300px;
            /* Lebar sidebar */
            flex-shrink: 0;
            /* Mencegah sidebar menyusut */
            background-color: #856404;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            color: black;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        .user-details {
            /* Kosong, styling diambil dari .user-name dan .user-contact */
        }

        .user-name {
            font-size: 1.4rem;
            margin-bottom: 0.3rem;
            word-break: break-word;
            color: white;
            /* Mencegah nama panjang merusak layout */
        }

        .user-contact {
            color: white;
            font-size: 0.9rem;
        }

        .transactions-scroll {
            /* [PERUBAHAN] Mengambil sisa ruang */
            flex: 1;
            overflow-x: auto;
            padding: 1.5rem;
            background-color: #1f2937;
        }

        .transactions-row {
            display: flex;
            gap: 1.5rem;
            padding-bottom: 0.5rem;
            /* Memberi ruang untuk scrollbar */
            width: max-content;
            /* Penting untuk horizontal scroll */
        }

        /* Add smooth scrollbar for Webkit browsers */
        .transactions-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .transactions-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .transactions-scroll::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 4px;
        }

        .transactions-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* === [END] CSS LAYOUT BARU === */


        .transaction-card {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: 0.3s;

            /* [GABUNGAN] Style kartu */
            min-width: 320px;
            max-width: 320px;
            border: 1px solid #eee;
            flex-shrink: 0;
            /* Mencegah kartu menyusut di dalam flex-row */
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.8rem;
            margin-bottom: 1rem;
        }

        .invoice-number {
            font-weight: bold;
            color: var(--primary-color);
        }

        .transaction-date {
            font-size: 0.85rem;
            color: var(--secondary-color);
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.3rem 0.7rem;
            border-radius: 8px;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-dikirim {
            background: #cce5ff;
            color: #004085;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
        }

        .customer-details {
            font-size: 0.9rem;
            color: #555;
        }

        .payment-info {
            display: flex;
            justify-content: space-between;
            margin: 1rem 0;
            font-weight: 600;
        }

        .products-section {
            margin-top: 1rem;
            border-top: 1px dashed #ddd;
            padding-top: 1rem;
        }

        .products-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.6rem;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .action-btn {
            flex: 1;
            padding: 0.6rem;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            transition: 0.3s;
        }

        .btn-confirm {
            background: var(--primary-color);
        }

        .btn-complete {
            background: var(--secondary-color);
        }

        .action-btn:hover {
            opacity: 0.9;
        }

        .empty-state {
            text-align: center;
            background: #fff;
            border-radius: 12px;
            padding: 4rem;
            color: var(--secondary-color);
        }

        .empty-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        @keyframes slideInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

@endsection