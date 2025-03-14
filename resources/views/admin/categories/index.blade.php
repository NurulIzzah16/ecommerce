@extends('layouts.admin')

@section('content')
<h2 class="mt-3">{{__('categories.categories')}}</h2>

<a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">{{__('categories.add')}}</a>

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
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

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table id="categoriesTable" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>{{__('categories.name')}}</th>
            <th>{{__('categories.description')}}</th>
            <th>{{__('categories.actions')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>
                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah kamu yakin ingin menghapus kategori ini?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
