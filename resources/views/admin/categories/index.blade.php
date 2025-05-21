@extends('layouts.admin')

@section('content')
<h2 class="mt-3">@lang('categories.categories')</h2>

<!-- Tombol Tambah, Export, dan Import -->
<div class="mb-3">
    @if (!in_array('categories.create', auth()->user()->role->permissions ?? []))
    <a href="{{ route('categories.create') }}" class="btn btn-primary">@lang('categories.add')</a>
    @endif
    <a href="{{ route('categories.export') }}" class="btn btn-success">Export</a>
    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importModal">Import</button>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('categories.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File Excel</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
                </form>
                <div class="mt-3">
                    <a href="{{ route('categories.downloadTemplate') }}" class="btn btn-secondary">Download Template Excel</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pesan Sukses -->
@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

<!-- Tabel Kategori -->
<table id="categoriesTable" class="table table-striped mt-3" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>@lang('categories.name')</th>
            <th>@lang('categories.description')</th>
            <th>@lang('categories.actions')</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>
                    @if (!in_array('categories.create', auth()->user()->role->permissions ?? []))
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    @endif
                    @if (!in_array('categories.delete', auth()->user()->role->permissions ?? []))
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah kamu yakin ingin menghapus kategori ini?')">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
