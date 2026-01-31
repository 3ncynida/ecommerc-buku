<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
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

        img {
            margin-top: 10px;
            width: 100px;
            border-radius: 6px;
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
        <h3>Edit Item</h3>

        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label>Nama Item</label>
            <input type="text" name="name" value="{{ $item->name }}" required>

            <label>Harga</label>
            <input type="number" name="price" value="{{ $item->price }}" required>

            <label>Gambar</label>
            <input type="file" name="image">

            <label>Kategori</label>
            <select name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>


            @if ($item->image)
                <img src="{{ asset('storage/' . $item->image) }}">
            @endif

            <button type="submit">Update</button>
        </form>
    </div>

</body>

</html>