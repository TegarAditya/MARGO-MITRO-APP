<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    use HasFactory;

    public function production_order()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function realisasi_details()
    {
        return $this->hasMany(RealisasiDetail::class);
    }
}
