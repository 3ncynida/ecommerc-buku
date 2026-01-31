<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        img {
            width: 80px;
            height: auto;
            border-radius: 6px;
        }
        a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2>Daftar Item</h2>

<button><a href="{{ route('items.create') }}">tambah</a></button>

<form method="GET">
    <select name="category_id" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</form>


<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if ($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $item->name }}</td>
                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('items.edit', $item->id) }}">Edit</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Data item belum ada</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
