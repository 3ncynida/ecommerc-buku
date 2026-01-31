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
        <h3>Tambah Item</h3>

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Nama Item</label>
            <input type="text" name="name" required>

            <label>Harga</label>
            <input type="number" name="price" required>

            <label>Kategori</label>
            <select name="category_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>


            <label>Gambar</label>
            <input type="file" name="image">

            <button type="submit">Simpan</button>
        </form>
    </div>

</body>

</html>