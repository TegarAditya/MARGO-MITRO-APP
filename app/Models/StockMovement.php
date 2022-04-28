<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public const TYPE_SELECT = [
        'order'    => 'Order',
        'faktur'    => 'Sales',
        'adjustment' => 'Adjustment',
    ];

    public $table = 'stock_movements';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'reference',
        'type',
        'product_id',
        'quantity',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function referensi() {
        if ($this->attributes['type'] === 'faktur') {
            return $this->belongsTo(Invoice::class, 'reference');
        } else if ($this->attributes['type'] === 'adjustment') {
            return $this->belongsTo(StockAdjustment::class, 'reference');
        } else if ($this->attributes['type'] === 'order') {
            return $this->belongsTo(Order::class, 'reference');
        }
    }

}
