<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;

class BukuCustomImport implements ToCollection, WithHeadingRow
{
    private $jenjang;
    private $kelas;
    private $halaman;
    private $units;
    private $cover;
    private $isi;

    public function __construct()
    {
        $this->jenjang = Category::select('id', 'name')->where('type', 'jenjang')->get();
        $this->kelas = Category::select('id', 'name')->where('type', 'kelas')->get();
        $this->halaman = Category::select('id', 'name')->where('type', 'halaman')->get();
        $this->isi = Category::select('id', 'name')->where('type', 'isi')->get();
        $this->cover = Brand::select('id', 'name')->get();
        $this->units = Unit::select('id', 'name')->get();
    }

    public function collection(Collection $rows)
    {
        $isi_array = $this->isi;
        $cover_array = $this->cover;

        $isik13 = array('31', '32');
        $isimerdeka = array('30', '32');
        $k13only = array('31');

        //k13 naskah sendiri
        $sdk13 = array("MMJ", "MP", "AL HUDA", "FATONAH", "ANNUR", "CILACAP PARYANTO", "BANYUMAS", "JUARA", "BRILIANT", "GEMILANG", "PANDAWA", "LAMONGAN", "PELANGI", "KRESNA", "APIN MAS", "MGMP SRAGEN");
        $smpk13 = array("MMJ", "MIKA", "PELANGI", "MGMP TUBAN", "MGMP KEDIRI", "MGMP MAGETAN", "MGMP TRENGGALEK", "SPORTIF", "MGMP CILACAP", "MGMP JEPARA", "GEMILANG");
        $smak13 = array("MMJ", "MASTER", "MGMP SOLO", "MP", "MGMP JAMBI", "MGMP GROBOGAN");
        $peminatank13 = array('MMJ');
        $smkk13 = array("MMJ", "MP", "MIKA", "MASTER");
        $mik13 = array("MMJ", "MP", "NU KENDAL");
        $mtsk13 = array("MMJ", "MP", "MGMP KEDIRI", "MGMP TUBAN");
        $mak13 = array('MMJ');

        //merdeka naskah sendiri
        $sdmerdeka = array("MMJ", "MP", "SI PINTAR", "MGMP PARYANTO");
        $smpmerdeka = array("MMJ", "MP", "SI PINTAR", "MGMP PARYANTO", "MIKA", "MASTER", "MGMP JAMBI", "MGMP GROBOGAN");
        $smamerdeka = array("MMJ", "MIKA", "MASTER", "MGMP JAMBI", "MGMP GROBOGAN");

        $semesters = array('7');


        set_time_limit(0);
        DB::beginTransaction();
        try {
            foreach($isimerdeka as $isi) {
                foreach($smamerdeka as $cover_name) {
                    foreach ($rows as $row) {
                        $jenjang = $this->jenjang->where('name', $row['jenjang'])->first();
                        $kelas = $this->kelas->where('name', $row['kelas'])->first();
                        $halaman = $this->halaman->where('name', $row['halaman'])->first();
                        $cover = $this->cover->where('name', $cover_name)->first();

                        foreach ($semesters as $semester) {
                            $product = Product::create([
                                'name' => $row['name'],
                                'description' => $row['name'],
                                'category_id' => 1,
                                'brand_id' => $cover->id,
                                'unit_id' => 1,
                                'jenjang_id' => $jenjang->id,
                                'kelas_id' => $kelas->id,
                                'halaman_id' => $halaman->id,
                                'isi_id' => $isi,
                                'hpp' => null,
                                'price' => $row['price'],
                                'finishing_cost' => null,
                                'stock' => 0,
                                'min_stock' => 0,
                                'tipe_pg' => 'non_pg',
                                'status' => 1,
                                'semester_id' => $semester
                            ]);

                            $product_pg = Product::create([
                                'name' => 'PG - '. $row['name'],
                                'description' => 'PG - '. $row['name'],
                                'category_id' => 1,
                                'brand_id' => $cover->id,
                                'unit_id' => 1,
                                'jenjang_id' => $jenjang->id,
                                'kelas_id' => $kelas->id,
                                'halaman_id' => $halaman->id,
                                'isi_id' => $isi,
                                'hpp' => null,
                                'price' => 6000,
                                'finishing_cost' => null,
                                'stock' => 0,
                                'min_stock' => 0,
                                'tipe_pg' => 'pg',
                                'status' => 1,
                                'semester_id' => $semester
                            ]);

                            $product->update([
                                'pg_id' => $product_pg->id
                            ]);
                        }
                    }
                }
            }
            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            dd('Relax you are doin fine', $e);
        }

        // foreach($isi_array as $isi) {
        //     foreach($cover_array as $cover) {
        //         foreach ($rows as $row) {
        //             $jenjang = $this->jenjang->where('name', $row['jenjang'])->first();
        //             $kelas = $this->kelas->where('name', $row['kelas'])->first();
        //             $halaman = $this->halaman->where('name', $row['halaman'])->first();

        //             $product = Product::create([
        //                 'name' => $row['name'],
        //                 'description' => $row['name'],
        //                 'category_id' => 1,
        //                 'brand_id' => $cover->id,
        //                 'unit_id' => 1,
        //                 'jenjang_id' => $jenjang->id,
        //                 'kelas_id' => $kelas->id,
        //                 'halaman_id' => $halaman->id,
        //                 'isi_id' => $isi->id,
        //                 'hpp' => null,
        //                 'price' => $row['price'],
        //                 'finishing_cost' => null,
        //                 'stock' => 0,
        //                 'min_stock' => 0,
        //                 'tipe_pg' => 'non_pg',
        //                 'status' => 1
        //             ]);

        //             $product_pg = Product::create([
        //                 'name' => 'PG - '. $row['name'],
        //                 'description' => 'PG - '. $row['name'],
        //                 'category_id' => 1,
        //                 'brand_id' => $cover->id,
        //                 'unit_id' => 1,
        //                 'jenjang_id' => $jenjang->id,
        //                 'kelas_id' => $kelas->id,
        //                 'halaman_id' => $halaman_pg->id,
        //                 'isi_id' => $isi->id,
        //                 'hpp' => null,
        //                 'price' => 6000,
        //                 'finishing_cost' => null,
        //                 'stock' => 0,
        //                 'min_stock' => 0,
        //                 'tipe_pg' => 'pg',
        //                 'status' => 1
        //             ]);

        //             $product->update([
        //                 'pg_id' => $product_pg->id
        //             ]);
        //         }
        //     }
        // }
    }
}
