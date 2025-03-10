@extends('layouts.admin')

@section('content')
<h2 class="mt-3">{{__('product.products')}}</h2>

<a href="{{ route('products.create') }}" class="btn btn-primary mb-3">{{__('product.add')}}</a>

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

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
