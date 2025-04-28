<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\File;
use App\Notifications\NewProduct;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images', 'category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $product = Product::create($request->only('name', 'description', 'price', 'stock', 'category_id'));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imagePath,
                ]);
            }
        }
        // Kirim notifikasi ke semua user, kecuali admin
        $users = User::where('role', '!=', 'admin')->get();  // Menyeleksi semua user yang bukan admin

        foreach ($users as $user) {
            // Kirimkan notifikasi
            $user->notify(new NewProduct($product));
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil dibuat.');
    }

    // Edit
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar yang dipilih untuk dihapus
        if ($request->has('delete_images')) {
            $imagesToDelete = $request->input('delete_images');
            foreach ($imagesToDelete as $imageId) {
                $image = ProductImage::findOrFail($imageId);
                if (Storage::exists('public/' . $image->image_url)) {
                    Storage::delete('public/' . $image->image_url);
                }
                $image->delete();
            }
        }

        // Upload gambar baru (jika ada)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');  // Jangan lupa tambahkan 'public'
                $product->images()->create(['image_url' => $path]);
            }
        }

        // Update data produk lainnya
        $product->update($request->only(['name', 'description', 'price', 'stock', 'category_id']));

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Cek apakah produk memiliki gambar
        if ($product->images) {
            foreach ($product->images as $image) {
                if ($image->image_url) {
                    if (Storage::exists($image->image_url)) {
                        Storage::delete($image->image_url);
                    }
                }
            }
            $product->images()->delete();
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return redirect()->route('products.index')->with('success', 'Produk berhasil diimport.');
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function downloadTemplate()
    {
        $path = public_path('templates/product_template.xlsx');
        if (File::exists($path)) {
            return response()->download($path);
        }
        return redirect()->back()->with('error', 'Template tidak ditemukan.');
    }
}
