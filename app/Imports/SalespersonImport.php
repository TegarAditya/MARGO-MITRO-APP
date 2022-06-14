<?php

namespace App\Imports;

use App\Models\Salesperson;
use App\Models\City;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalespersonImport implements ToCollection, WithHeadingRow
{
    private $cities;

    public function __construct()
    {
        $this->cities = City::select('id', 'name')->get();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $salesperson = Salesperson::create([
                'name' => $row['name'],
                'telephone' => $row['telepon'],
                'company' => $row['badan_usaha'],
                'alamat' => $row['alamat']
            ]);
            $cities = explode(";", $row['area_pemasaran']);
            $area_pemasaran = [];
            foreach($cities as $value) {
                $city = $this->cities->where('name', $value)->first();
                if ($city) {
                    array_push($area_pemasaran, $city->id);
                }
            }
            $salesperson->area_pemasarans()->sync($area_pemasaran);
        }
    }
}
