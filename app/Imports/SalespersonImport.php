<?php

namespace App\Imports;

use App\Models\Salesperson;
use App\Models\City;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

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
    public function model(array $row)
    {
        foreach ($rows as $row) {
            $city = $this->cities->where('name', $row['area'])->first();
            $salesperson = Salesperson::create([
                'name' => row['name']
            ]);
            $salesperson->area_pemasarans()->sync($request->input('area_pemasarans', []));
        }
    }
}
