@extends('layouts.admin')

@section('content')
    <h2 class="mt-3">{{__('product.edit product')}}</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Kategori Produk -->
        <div class="form-group">
            <label for="category_id">{{__('product.category')}}</label>
            <select name="category_id" class="form-control select2" required>
                <option value="">{{ __('product.select a category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Nama Produk -->
        <div class="form-group">
            <label for="name">{{__('product.product name')}}</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <!-- Deskripsi Produk -->
        <div class="form-group">
            <label for="description">{{__('product.description')}}</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>

        <!-- Harga Produk -->
        <div class="form-group">
            <label for="price">{{__('product.price')}}</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
        </div>

        <!-- Stok Produk -->
        <div class="form-group">
            <label for="stock">{{__('product.stock')}}</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
        </div>

        <!-- Upload Gambar Baru -->
        <div class="form-group mt-3">
            <label for="images">{{__('product.product images')}}</label>
            <input type="file" name="images[]" class="form-control" multiple>
            <small class="form-text text-muted">{{__('product.nb2')}}</small>
        </div>

        <!-- Gambar Produk Yang Sudah Ada -->
        <div class="form-group mt-3">
            <div class="d-flex flex-wrap">
                @foreach ($product->images as $image)
                    <div class="m-2 text-center">
                        <img src="{{ asset('storage/' . $image->image_url) }}" alt="Gambar Produk">
                        <div>
                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                            <label>{{__('product.delete')}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tombol Update -->
        <button type="submit" class="btn btn-primary mt-3">{{__('product.update product')}}</button>
    </form>
@endsection
