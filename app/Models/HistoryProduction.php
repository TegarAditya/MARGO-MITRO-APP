<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoryProduction extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const TYPE_SELECT = [
        'preorder' => 'Preorder',
        'order'    => 'Order',
        'cetak'    => 'Cetak',
        'finishing' => 'Finishing'
    ];

    public $table = 'history_productions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'reference_id',
        'type',
        'summary_order_id',
        'product_id',
        'pesanan',
        'stock',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function reference()
    {
        if ($this->type === 'preorder') {
            return $this->belongsTo(Preorder::class, 'reference_id');
        } else if ($this->type === 'order') {
            return $this->belongsTo(Order::class, 'reference_id');
        } else if ($this->type === 'cetak') {
            return $this->belongsTo(Realisasi::class, 'reference_id');
        } else if ($this->type === 'finishing') {
            return $this->belongsTo(ProductionOrder::class, 'reference_id');
        }
    }

    public function summary_order()
    {
        return $this->belongsTo(SummaryOrder::class, 'summary_order_id');
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
