<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Salesperson;
use App\Models\Category;
use App\Models\CustomPrice;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;

class CustomPriceImport implements ToCollection, WithHeadingRow
{
    // private $halaman;
    // private $sales;

    public function __construct()
    {
        // $this->halaman = Category::select('id', 'name')->where('type', 'halaman')->get();
        // $this->sales = Salesperson::select('id', 'name')->oldest()->get();
    }

    public function collection(Collection $rows)
    {
        // halaman 48, 64, 96, 112
        // $hal = array("hal_48" => 26, "hal_64" => 27, "hal_96" => 28, "hal_112" => 29);
        // $hal = array("hal_64" => 27, "hal_96" => 28);
        // $hal = array("hal_64" => 27, "hal_96" => 28, "hal_112" => 29);
        $hal = array("hal_112" => 29);
        // $sales = $this->sales;

        set_time_limit(0);
        DB::beginTransaction();
        try {
            $counter = 1;
            foreach($rows as $row) {
                foreach($hal as $key => $value) {
                    if ($row[$key] == null) {
                        continue;
                    }
                    CustomPrice::create([
                        // 'nama' => 'HARGA CASH',
                        // 'nama' => 'HARGA RETUR',
                        // 'nama' => 'HARGA RETUR(BENDING)',
                        // 'nama' => 'HARGA NON RETUR',
                        'nama' => 'HARGA NON RETUR(BENDING)',
                        'sales_id' => $counter,
                        'kategori_id' => $value,
                        'harga' => $row[$key]
                    ]);
                }
                $counter++;
            }
            DB::commit();
        }  catch (Exception $e) {
            DB::rollback();
            dd('Relax you are doin fine', $e);
        }
    }
}
