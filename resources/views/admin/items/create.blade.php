<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            width: 400px;
            margin: auto;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="card">
        <h3>Tambah Buku</h3>

        @if ($errors->any())
            <div style="background:#ffe6e6; padding:10px; border-radius:6px; margin-bottom:15px;">
                <ul style="margin:0; padding-left:20px; color:#b00020;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div style="background:#ffe6e6; padding:10px; border-radius:6px; margin-bottom:15px;">
                    <ul style="margin:0; padding-left:20px; color:#b00020;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <label>Nama Buku</label>
            <input type="text" name="name" value="{{ old('name') }}" required>

            <label>Author</label>
            <select name="author_id" required>
                <option value="">-- Pilih Author --</option>
                @foreach ($author as $author)
                    <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>

            <label>Harga</label>
            <input type="number" name="price" value="{{ old('price') }}" required>

            <label>Stok</label>
            <input type="number" name="stok" value="{{ old('stok') }}" required>

            <label>Kategori</label>
            <select name="category_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <label>Deskripsi</label>
            <textarea name="description" rows="4"
                style="width:100%; margin-top:5px;">{{ old('description') }}</textarea>

            <label>Gambar</label>
            <input type="file" name="image">

            <button type="submit">Simpan</button>
        </form>

    </div>

</body>

</html>