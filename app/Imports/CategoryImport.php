<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CategoryImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {

        if (!isset($row['name']) || empty(trim($row['name']))) {
            return null;
        }

        $category = Category::firstOrNew(['name' => $row['name']]);

        if (isset($row['description'])) {
            $category->description = $row['description'];
        }

        $category->save();

        return $category;
    }
}
