<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BukuImport implements ToModel, WithHeadingRow
{
    private $jenjang;
    private $kelas;
    private $halaman;
    private $brands;
    private $units;

    public function __construct()
    {
        $this->jenjang = Category::select('id', 'name')->where('type', 'jenjang')->get();
        $this->kelas = Category::select('id', 'name')->where('type', 'kelas')->get();
        $this->halaman = Category::select('id', 'name')->where('type', 'halaman')->get();
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
        $jenjang = $this->jenjang->where('name', $row['jenjang'])->first();
        $kelas = $this->kelas->where('name', $row['kelas'])->first();
        $halaman = $this->halaman->where('name', $row['halaman'])->first();
        $brand = $this->brands->where('name', $row['brand'])->first();
        $unit = $this->units->where('name', $row['unit'])->first();

        return new Product([
            'name' => $row['name'],
            'description' => $row['description'],
            'category_id' => 1,
            'brand_id' => $brand->id,
            'unit_id' => $unit->id,
            'jenjang_id' => $jenjang->id,
            'kelas_id' => $kelas->id,
            'halaman_id' => $halaman->id,
            'hpp' => $row['hpp'],
            'price' => $row['price'],
            'finishing_cost' => $row['finishing_cost'],
            'stock' => $row['stock'],
            'min_stock' => $row['min_stock'],
            'status' => 1
        ]);
    }
}
