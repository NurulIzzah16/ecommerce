@extends('layouts.admin')

@section('content')
<h2 class="mt-3">{{__('product.products')}}</h2>

<!-- Tombol Tambah, Export, dan Import Produk -->
<div class="d-flex mb-3">
    <a href="{{ route('products.create') }}" class="btn btn-primary me-2">{{__('product.add')}}</a>
    <a href="{{ route('products.export') }}" class="btn btn-success me-2">Export</a>
    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">Import</button>
</div>

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<!-- Modal untuk Import Produk -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <div class="mt-3">
                    <a href="{{ route('products.downloadTemplate') }}" class="btn btn-secondary">Download Template</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Produk -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? 'Tidak ada kategori' }}</td>
                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    @if ($product->images->isNotEmpty())
                        <div class="d-flex flex-wrap">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_url) }}" alt="Gambar Produk" width="100" class="m-1">
                            @endforeach
                        </div>
                    @else
                        Tidak ada gambar
                    @endif
                </td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">{{__('product.delete')}}</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
