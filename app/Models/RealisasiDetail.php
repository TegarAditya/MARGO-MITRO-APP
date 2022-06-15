<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiDetail extends Model
{
    use HasFactory;

    public function realisasi()
    {
        return $this->belongsTo(Realisasi::class);
    }

    public function production_order()
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function production_order_detail()
    {
        return $this->belongsTo(ProductionOrderDetail::class, 'po_detail_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
