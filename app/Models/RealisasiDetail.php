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
        'finishing_order_id',
        'fo_detail_id',
        'product_id',
        'qty',
        'price',
        'total',
    ];

    public function realisasi()
    {
        return $this->belongsTo(Realisasi::class);
    }

    public function finishing_order()
    {
        return $this->belongsTo(FinishingOrder::class);
    }

    public function finishing_order_detail()
    {
        return $this->belongsTo(FinishingOrderDetail::class, 'fo_detail_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
