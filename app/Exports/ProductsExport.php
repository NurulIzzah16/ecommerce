<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with('images')->get(); // Mengambil produk beserta gambarnya
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Description', 'Price', 'Stock', 'Category ID', 'Images'];
    }

    public function map($product): array
    {
        // Mengambil URL gambar dan menggabungkannya dengan tanda koma jika lebih dari satu gambar
        $imageUrls = $product->images->pluck('image_url')->map(function ($imageUrl) {
            return asset('storage/' . $imageUrl); // Menampilkan URL lengkap
        })->implode(', ');

        return [
            $product->id,
            $product->name,
            $product->description,
            $product->price,
            $product->stock,
            $product->category_id,
            $imageUrls,
        ];
    }
}
