<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Productionperson;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductionpersonImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Productionperson([
            'name' => $row['name'],
            'type' => $row['type'],
            'contact' => $row['telepon'],
            'company' => $row['badan_usaha'],
            'alamat' => $row['alamat']
        ]);
    }
}
