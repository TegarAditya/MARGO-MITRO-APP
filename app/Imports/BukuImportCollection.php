<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;

class BukuImportCollection implements ToCollection, WithHeadingRow
{
    private $jenjang;
    private $kelas;
    private $halaman;
    private $brands;
    private $units;
    private $isi;

    public function __construct()
    {
        $this->jenjang = Category::select('id', 'name')->where('type', 'jenjang')->get();
        $this->kelas = Category::select('id', 'name')->where('type', 'kelas')->get();
        $this->halaman = Category::select('id', 'name')->where('type', 'halaman')->get();
        $this->isi = Category::select('id', 'name')->where('type', 'isi')->get();
        $this->brands = Brand::select('id', 'name')->get();
        $this->units = Unit::select('id', 'name')->get();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        set_time_limit(0);
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $jenjang = $this->jenjang->where('name', $row['jenjang'])->first();
                $kelas = $this->kelas->where('name', $row['kelas'])->first();
                $halaman = $this->halaman->where('name', $row['halaman'])->first();
                $cover = $this->cover->where('name', $row['cover'])->first();
                $isi = $this->isi->where('name', $row['isi'])->first();

                $product = Product::create([
                    'name' => $row['name'],
                    'description' => $row['name'],
                    'category_id' => 1,
                    'brand_id' => $cover->id,
                    'unit_id' => 1,
                    'jenjang_id' => $jenjang->id,
                    'kelas_id' => $kelas->id,
                    'halaman_id' => $halaman->id,
                    'isi_id' => $isi->id,
                    'hpp' => $row['hpp'],
                    'price' => $row['price'],
                    'finishing_cost' => null,
                    'stock' => 0,
                    'min_stock' => 0,
                    'tipe_pg' => 'non_pg',
                    'status' => 1,
                    'semester_id' => $row['semester']
                ]);

                $product_pg = Product::firstOrCreate([
                    'name' => 'PG - '. $row['name'],
                    'category_id' => 1,
                    'brand_id' => $cover->id,
                    'jenjang_id' => $jenjang->id,
                    'kelas_id' => $kelas->id,
                    'halaman_id' => $halaman->id,
                    'isi_id' => $isi->id,
                    'tipe_pg' => 'non_pg',
                    'semester_id' => $row['semester']
                ], [
                    'description' => 'PG - '. $row['name'],
                    'unit_id' => 1,
                    'hpp' => null,
                    'price' => 6000,
                    'finishing_cost' => null,
                    'stock' => 0,
                    'min_stock' => 0,
                    'status' => 1,
                ]);

                $product->update([
                    'pg_id' => $product_pg->id
                ]);
            }
            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            dd('Relax you are doin fine', $e);
        }
    }
}
