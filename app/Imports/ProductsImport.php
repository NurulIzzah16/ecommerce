<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage; // Jangan lupa import ini
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            // Cek apakah name dan price terisi
            if (!isset($row['name']) || empty(trim($row['name'])) || !isset($row['price'])) {
                return null; // Abaikan baris jika name atau price kosong
            }

            // Ambil ID kategori jika ada, kalau tidak ada isi dengan null
            $categoryId = $row['category_id'] ?? null;

            // Cek apakah produk sudah ada berdasarkan nama
            $existingProduct = Product::where('name', $row['name'])->first();

            if ($existingProduct) {
                // Update produk yang sudah ada
                $existingProduct->update([
                    'price' => $row['price'],
                    'stock' => $row['stock'] ?? $existingProduct->stock,
                    'category_id' => $categoryId,
                    'description' => $row['description'] ?? $existingProduct->description,
                ]);

                $product = $existingProduct;
            } else {
                // Buat produk baru jika belum ada
                $product = Product::create([
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'stock' => $row['stock'] ?? 0,
                    'category_id' => $categoryId,
                    'description' => $row['description'] ?? null,
                ]);
            }

            // Menyimpan gambar di tabel product_images
            if (isset($row['images'])) {
                $images = explode(',', $row['images']); // Memecah gambar dengan tanda koma
                foreach ($images as $image) {
                    $image = trim($image); // Menghilangkan spasi berlebih
                    if (!empty($image)) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_url' => $image,
                        ]);
                    }
                }
            }

            return null; // Tidak perlu mengembalikan model karena kita sudah menyimpannya
        } catch (\Exception $e) {
            Log::error("Gagal mengimpor produk: " . $e->getMessage());
            return null;
        }
    }
}
