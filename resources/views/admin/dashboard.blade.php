@extends('layouts.admin')
@section('content')
    <div class="admin-dashboard">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Dashboard Overview</h1>
            <p>Monitor dan kelola sistem dari pusat kontrol ini</p>

            <a href="{{ route('admin.chat') }}" class="chat-badge">
                💬 Admin Chat
                @if(session('admin_unread_users_count'))
                    <span class="notification-badge">{{ session('admin_unread_users_count') }}</span>
                @endif
                <span class="online-indicator">● 3 online</span>
            </a>
        </div>

        <!-- Main Grid -->
        <div class="main-grid">
            <!-- Kelola User -->
            <a href="{{ route('admin.users.index') }}" class="card">
                <h3>👥 Kelola User</h3>
                <p>Kelola User yang sudah terdaftar</p>
            </a>

            <!-- List Buku -->
            <a href="{{ route('admin.produk.index') }}" class="card">
                <h3>📦 List Buku</h3>
                <p>Tambah, Edit, dan Hapus Buku</p>
            </a>

            <a href="{{ route('admin.kategori.index') }}" class="card">
                <h3>Kategori Buku</h3>
                <p>Tambah, Edit, dan Hapus Buku</p>
            </a>

            <!-- Konfirmasi Pesanan -->
            <a href="{{ route('admin.transactions.index') }}" class="card urgent">
                <h3>🛒 Konfirmasi Pesanan</h3>
                <p>Proses pesanan user</p>
            </a>

            <!-- Statistik -->
            <div class="card stats">
                <h3>📊 Statistik</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">{{ \App\Models\User::count() }}</span>
                        <span class="stat-label">Total User</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ \App\Models\Produk::count() }}</span>
                        <span class="stat-label">Total Buku</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ \App\Models\Transaction::count() }}</span>
                        <span class="stat-label">Total Transaksi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-color: #5D4037;
            --secondary-color: #A1887F;
            --background-color: #F5F5F5;
            --card-background: #FFFFFF;
            --font-heading: 'Lora', serif;
            --font-body: 'Poppins', sans-serif;
        }

        body {
            font-family: var(--font-body);
            background-color: var(--background-color);
            color: var(--primary-color);
        }

        .admin-dashboard {
            padding: 2rem;
        }

        .welcome-section {
            background: var(--card-background);
            padding: 2rem;
            border-left: 5px solid var(--secondary-color);
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .welcome-section h1 {
            font-family: var(--font-heading);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            color: #424242;
        }

        /* Admin Chat Badge */
        .chat-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--secondary-color);
            color: #fff;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #d32f2f;
            color: white;
            min-width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
            padding: 0 6px;
            box-sizing: border-box;
        }

        .chat-badge:hover {
            background: var(--primary-color);
        }

        .online-indicator {
            color: #4CAF50;
            font-weight: bold;
            font-size: 0.8rem;
        }

        .main-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .card {
            background: var(--card-background);
            padding: 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            color: var(--primary-color);
            border: 2px solid var(--secondary-color);
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

            .card:hover {
                background: var(--secondary-color);
                color: #fff;
                transform: translateY(-5px);
            }

            .urgent {
                border-color: #d32f2f;
            }

            .urgent:hover {
                background: #d32f2f;
                border-color: #b71c1c;
            }
                .stats {
                    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                    color: #fff;
                }

                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
                    gap: 1rem;
                    margin-top: 1rem;
                }

                .stat-item {
                    text-align: center;
                }

                .stat-number {
                    display: block;
                    font-size: 1.5rem;
                    font-weight: bold;
                }

                .stat-label {
                    display: block;
                    font-size: 0.8rem;
                    opacity: 0.9;
                }
        </style>
@endsection