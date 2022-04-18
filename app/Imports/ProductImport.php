<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    private $categories;
    private $brands;
    private $units;

    public function __construct()
    {
        $this->categories = Category::select('id', 'name')->get();
        $this->brands = Brand::select('id', 'name')->get();
        $this->units = Unit::select('id', 'name')->get();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $category = $this->categories->where('name', $row['category'])->first();
        $brand = $this->brands->where('name', $row['brand'])->first();
        $unit = $this->units->where('name', $row['unit'])->first();

        return new Product([
            'name' => $row['name'],
            'description' => $row['description'],
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'unit_id' => $unit->id,
            'hpp' => $row['hpp'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'min_stock' => $row['min_stock'],
            'status' => 1
        ]);
    }
}
