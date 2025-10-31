@extends('layouts.admin')

@section('content')
    <div class="form-container">
        <div class="form-header">
            <div class="header-content">
                <div class="header-info">
                    <h1 class="page-title">
                        <span class="title-icon">➕</span>
                        Tambahkan Judul Buku
                    </h1>
                    <p class="page-subtitle">Tambahkan Judul Buku baru</p>
                </div>
            </div>
        </div>

        <div class="form-wrapper">
            <div class="form-card">
                <div class="form-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%"></div>
                    </div>
                    <div class="progress-text">Lengkapi formulir di bawah ini</div>
                </div>

                <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data"
                    id="productForm">
                    @csrf

                    <div class="form-group">
                        <label for="nama" class="form-label">
                            <span class="label-icon">📦</span>
                            Judul Buku
                        </label>
                        <div class="input-wrapper">
                            <input type="text" name="nama" id="nama" class="form-input @error('nama') error @enderror"
                                value="{{ old('nama') }}" placeholder="Masukan Judul Buku" required>
                            <div class="input-icon">📝</div>
                        </div>
                        @error('nama')
                            <div class="error-message">
                                <span class="error-icon">⚠️</span>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="field-hint">Pilih nama yang jelas dan deskriptif untuk Judul Buku.</div>
                    </div>

                    <div class="form-group">
                        <label for="kategori_id" class="form-label">
                            <span class="label-icon">🏷️</span>
                            Category
                        </label>
                        <div class="select-wrapper">
                            <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') error @enderror" required>
                                <option value="">Select a category</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="select-icon">🔽</div>
                        </div>
                        @error('kategori_id')
                            <div class="error-message">
                                <span class="error-icon">⚠️</span>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="field-hint">Select the most appropriate category for this product</div>
                    </div>

                    <div class="form-group">
                        <label for="harga" class="form-label">
                            <span class="label-icon">💰</span>
                            Harga
                        </label>
                        <div class="input-wrapper price-wrapper">
                            <div class="price-currency">Rp</div>
                            <input type="number" name="harga" id="harga"
                                class="form-input price-input @error('harga') error @enderror" value="{{ old('harga') }}"
                                placeholder="0" min="0" step="1000" required>
                            <div class="input-icon">💸</div>
                        </div>
                        @error('harga')
                            <div class="error-message">
                                <span class="error-icon">⚠️</span>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="field-hint">Tetapkan harga jual untuk buku ini.</div>
                    </div>

                    <div class="form-group">
                        <label for="foto" class="form-label">
                            <span class="label-icon">📷</span>
                            Foto Buku
                        </label>
                        <div class="file-upload-wrapper">
                            <div class="file-drop-zone" id="dropZone">
                                <div class="file-upload-content">
                                    <div class="upload-icon">📁</div>
                                    <h4>Letakkan gambar buku Anda di sini</h4>
                                    <p>atau <span class="upload-link">Cari File</span></p>
                                    <div class="upload-requirements">
                                        JPG, PNG, GIF maksimal 5MB
                                    </div>
                                </div>
                                <input type="file" name="foto" id="foto" class="file-input @error('foto') error @enderror"
                                    accept="image/*">
                            </div>

                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <div class="preview-overlay">
                                    <button type="button" class="remove-image" id="removeImage">
                                        <span>🗑️</span>
                                    </button>
                                </div>
                                <div class="image-info">
                                    <span id="fileName"></span>
                                    <span id="fileSize"></span>
                                </div>
                            </div>
                        </div>
                        @error('foto')
                            <div class="error-message">
                                <span class="error-icon">⚠️</span>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="field-hint">Upload foto berkualitas tinggi untuk mewakili buku.</div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
                            <span class="btn-icon">←</span>
                            Kembali ke Daftar Buku
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="btn-icon">💾</span>
                            <span class="btn-text">Simpan Buku</span>
                            <div class="btn-loader" style="display: none;">
                                <div class="spinner"></div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("productForm");
            const inputs = form.querySelectorAll("input[required]");
            const progressFill = document.querySelector(".progress-fill");
            const progressText = document.querySelector(".progress-text");

            // === Progress Bar ===
            function updateProgress() {
                let filled = 0;
                let total = inputs.length;

                inputs.forEach(input => {
                    if (input.type === "file") {
                        if (input.files.length > 0) {
                            filled++;
                        }
                    } else if (input.value.trim() !== "") {
                        filled++;
                    }
                });

                // Hitung progres
                const progress = Math.round((filled / total) * 100);

                progressFill.style.width = progress + "%";
                progressText.textContent = `Progres: ${progress}% selesai`;
            }



            inputs.forEach(input => {
                input.addEventListener("input", updateProgress);
                if (input.type === "file") {
                    input.addEventListener("change", updateProgress);
                }
            });


            form.addEventListener("submit", function () {
                document.getElementById("submitBtn").disabled = true;
                document.querySelector(".btn-text").textContent = "Menyimpan...";
                document.querySelector(".btn-loader").style.display = "inline-block";
            });

            // === Image Preview ===
            const fotoInput = document.getElementById("foto");
            // DIGANTI: Memperbaiki selector dari .dropzone menjadi .file-drop-zone
            const dropzone = document.querySelector(".file-drop-zone"); // bagian upload
            const imagePreview = document.getElementById("imagePreview");
            const previewImg = document.getElementById("previewImg");
            const fileName = document.getElementById("fileName");
            const fileSize = document.getElementById("fileSize");
            const removeImage = document.getElementById("removeImage");

            fotoInput.addEventListener("change", function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        fileName.textContent = "📄 " + file.name;
                        fileSize.textContent = (file.size / 1024).toFixed(1) + " KB";

                        // tampilkan preview
                        imagePreview.style.display = "block";
                        // DIHAPUS: dropzone.style.display = "none"; (Agar dropzone tetap terlihat)
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeImage.addEventListener("click", function () {
                fotoInput.value = "";
                imagePreview.style.display = "none";
                // DIHAPUS: dropzone.style.display = "block"; (Karena tidak pernah disembunyikan)
                previewImg.src = "";
                fileName.textContent = "";
                fileSize.textContent = "";
                updateProgress();
            });
        });
    </script>
@endpush


<style>
    :root {
        --primary-color: #5D4037;
        --secondary-color: #A1887F;
        --background-color: #F5F5F5;
    }

    /* Layout dasar */
    body,
    .form-container {
        font-family: 'Poppins', sans-serif;
        background: var(--background-color);
    }



    /* Header */
    .form-header {
        background: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .page-title {
        font-size: 1.8rem;
        font-family: 'Lora', serif;
        margin: 0;
        color: var(--primary-color);
    }

    .page-subtitle {
        color: var(--secondary-color);
        font-size: 0.95rem;
    }

    .breadcrumb-nav {
        font-size: 0.9rem;
        color: var(--secondary-color);
        margin-top: 0.5rem;
    }

    .breadcrumb-item.active {
        font-weight: 600;
        color: var(--primary-color);
    }

    /* Form wrapper */
    .form-wrapper {
        display: grid;
        /* grid-template-columns: 2fr 1fr; */
        gap: 2rem;
    }

    .form-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    /* Progress bar */
    .form-progress {
        margin-bottom: 1.5rem;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #eee;
        border-radius: 6px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        width: 0;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transition: width 0.3s ease-in-out;
    }

    .progress-text {
        font-size: 0.85rem;
        margin-top: 0.5rem;
        color: var(--secondary-color);
    }

    /* Form group */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.4rem;
    }

    .input-wrapper,
    .select-wrapper,
    .price-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        transition: 0.3s;
        font-size: 0.95rem;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(93, 64, 55, 0.2);
    }

    .price-currency {
        position: absolute;
        left: 10px;
        font-weight: 600;
        color: var(--secondary-color);
    }

    .price-input {
        padding-left: 35px;
    }

    .input-icon,
    .select-icon {
        position: absolute;
        right: 10px;
        font-size: 1rem;
        color: var(--secondary-color);
    }

    .field-hint {
        font-size: 0.8rem;
        color: #777;
        margin-top: 0.3rem;
    }

    /* Error state */
    .error {
        border-color: #d32f2f !important;
    }

    .error-message {
        color: #d32f2f;
        font-size: 0.8rem;
        margin-top: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* ======================================== */
    /* PERUBAHAN CSS DI SINI          */
    /* ======================================== */

    /* File upload */
    .file-upload-wrapper {
        margin-top: 0.5rem;
        /* 1. Menggunakan Flexbox untuk layout berdampingan */
        display: flex;
        align-items: flex-start; /* Meratakan item ke atas */
        gap: 1.5rem; /* Memberi jarak antara dropzone dan preview */
    }

    .file-drop-zone {
        border: 2px dashed #ccc;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        background: #fafafa;
        transition: 0.3s;
        
        /* 2. Biarkan dropzone mengisi sisa ruang */
        flex-grow: 1; 
    }

    .file-drop-zone.drag-over {
        border-color: var(--primary-color);
        background: #f0eae7;
    }

    .upload-icon {
        font-size: 2rem;
    }

    .upload-link {
        color: var(--primary-color);
        font-weight: 600;
        cursor: pointer;
    }

    .image-preview {
        position: relative;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        text-align: center;
        background: #f9f9f9;

        /* 3. Tentukan ukuran preview yang tetap */
        width: 150px;
        height: 150px;

        /* 4. Mencegah preview 'menyusut' */
        flex-shrink: 0; 
        
        /* 5. Hapus margin-top, 'gap' sudah mengatur jarak */
        margin-top: 0; 
    }

    .image-preview img {
        /* 6. Paksa gambar mengisi kotak 150x150px */
        width: 100%;
        height: 100%;
        /* 7. Potong gambar agar rapi (tidak penyok) */
        object-fit: cover; 
        display: block;
    }
    
    /* ======================================== */
    /* AKHIR PERUBAHAN CSS               */
    /* ======================================== */


    .preview-overlay {
        position: absolute;
        top: 8px;
        right: 8px;
    }

    .remove-image {
        background: #d32f2f;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #fff;
    }

    .image-info {
        font-size: 0.8rem;
        padding: 0.5rem;
        background: #fafafa;
        border-top: 1px solid #eee;
        
        /* Tambahan: Sembunyikan info file jika terlalu panjang */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: 0.3s;
        cursor: pointer;
    }

    .btn-primary {
        background: var(--primary-color);
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background: var(--secondary-color);
    }

    .btn-secondary {
        background: #e0e0e0;
        color: #333;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: #ccc;
    }

    .btn-loader .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid #fff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    /* Help card (Tidak ada di create.blade.php Anda, tapi saya biarkan) */
    .help-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        font-size: 0.9rem;
        line-height: 1.5;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .help-card h4 {
        margin-bottom: 1rem;
        color: var(--primary-color);
    }

    .help-card ul {
        padding-left: 1.2rem;
    }

    .help-card li {
        margin-bottom: 0.5rem;
    }
</style>