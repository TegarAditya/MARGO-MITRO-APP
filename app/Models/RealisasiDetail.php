<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RealisasiDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Auditable;

    protected $fillable = [
        'realisasi_id',
        'production_order_id',
        'po_detail_id',
        'product_id',
        'qty',
        'price',
        'total',
    ];

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
