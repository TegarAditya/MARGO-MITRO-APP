<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOrderDetail extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'production_order_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'production_order_id',
        'product_id',
        'order_qty',
        'prod_qty',
        'ongkos_satuan',
        'ongkos_total',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'ongkos_satuan' => 'double',
        'ongkos_total' => 'double',
    ];

    public function production_order()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
