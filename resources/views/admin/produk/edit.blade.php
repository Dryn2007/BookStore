@extends('layouts.admin')

@section('content')
    <div class="form-container">
        <!-- Simplified Header -->
        <div class="form-header">
            <h1 class="page-title">✏️ Edit Buku</h1>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <form action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf
                @method('PUT')

                <!-- Judul Buku -->
                <div class="form-group">
                    <label for="nama" class="form-label">Judul Buku</label>
                    <input type="text" name="nama" id="nama" class="form-input @error('nama') error @enderror"
                        value="{{ old('nama', $produk->nama) }}" placeholder="Masukan judul buku" required>
                    @error('nama')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') error @enderror" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $produk->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Harga -->
                <div class="form-group">
                    <label for="harga" class="form-label">Harga</label>
                    <div class="price-wrapper">
                        <span class="price-prefix">Rp</span>
                        <input type="number" name="harga" id="harga"
                            class="form-input price-input @error('harga') error @enderror"
                            value="{{ old('harga', $produk->harga) }}" placeholder="0" min="0" step="1000" required>
                    </div>
                    @error('harga')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Current Image -->
                @if($produk->foto)
                    <div class="form-group">
                        <label class="form-label">Foto Saat Ini</label>
                        <div class="current-image-display">
                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="Current Image">
                        </div>
                    </div>
                @endif

                <!-- Foto Buku -->
                <div class="form-group">
                    <label for="foto" class="form-label">Update Foto (Opsional)</label>
                    <div class="file-upload-area">
                        <input type="file" name="foto" id="foto" class="file-input @error('foto') error @enderror" accept="image/*">
                        <label for="foto" class="file-label" id="fileLabel">
                            <span class="upload-icon">�</span>
                            <span class="upload-text">Pilih gambar baru atau drop disini</span>
                            <span class="upload-hint">JPG, PNG, GIF - Max 5MB</span>
                        </label>
                        <div class="image-preview" id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                            <button type="button" class="remove-btn" id="removeImage">×</button>
                        </div>
                    </div>
                    @error('foto')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">← Kembali</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Update Buku</span>
                        <span class="btn-loader" style="display: none;">⏳</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form = document.getElementById("productForm");
                const fotoInput = document.getElementById("foto");
                const imagePreview = document.getElementById("imagePreview");
                const previewImg = document.getElementById("previewImg");
                const removeImage = document.getElementById("removeImage");
                const fileLabel = document.getElementById("fileLabel");

                // Image Preview
                fotoInput.addEventListener("change", function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewImg.src = e.target.result;
                            imagePreview.style.display = "block";
                            fileLabel.style.display = "none";
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Remove Image
                removeImage.addEventListener("click", function () {
                    fotoInput.value = "";
                    imagePreview.style.display = "none";
                    fileLabel.style.display = "flex";
                    previewImg.src = "";
                });

                // Form Submit
                form.addEventListener("submit", function () {
                    const submitBtn = document.getElementById("submitBtn");
                    submitBtn.disabled = true;
                    document.querySelector(".btn-text").style.display = "none";
                    document.querySelector(".btn-loader").style.display = "inline-block";
                });
            });
        </script>
    @endpush

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

        /* Container */
        .form-container {
            max-width: 700px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* Header - Minimalist */
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.8rem;
            font-family: var(--font-heading);
            margin: 0;
            color: var(--primary-color);
        }

        /* Form Card - Clean */
        .form-card {
            background: var(--card-background);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Form Group - Simplified */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        /* Inputs - Clean Design */
        .form-input,
        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s;
            font-size: 0.95rem;
            font-family: var(--font-body);
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(93, 64, 55, 0.1);
        }

        /* Price Input */
        .price-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .price-prefix {
            position: absolute;
            left: 1rem;
            font-weight: 600;
            color: var(--secondary-color);
            pointer-events: none;
        }

        .price-input {
            padding-left: 3rem;
        }

        /* Current Image Display */
        .current-image-display {
            max-width: 300px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .current-image-display img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* File Upload - Modern & Clean */
        .file-upload-area {
            position: relative;
        }

        .file-input {
            display: none;
        }

        .file-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
            border: 2px dashed var(--secondary-color);
            border-radius: 12px;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .file-label:hover {
            border-color: var(--primary-color);
            background: #f5f5f5;
        }

        .upload-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .upload-text {
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .upload-hint {
            font-size: 0.85rem;
            color: #777;
        }

        /* Image Preview - Compact */
        .image-preview {
            position: relative;
            margin-top: 1rem;
            border-radius: 12px;
            overflow: hidden;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        .image-preview img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 12px;
        }

        .remove-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 32px;
            height: 32px;
            background: rgba(211, 47, 47, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn:hover {
            background: #b71c1c;
            transform: scale(1.1);
        }

        /* Error Message */
        .error {
            border-color: #d32f2f !important;
        }

        .error-message {
            color: #d32f2f;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Form Actions - Clean */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: #fff;
            flex: 1;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.2);
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #ccc;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-container {
                padding: 1rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-primary {
                order: -1;
            }
        }
    </style>
@endsection