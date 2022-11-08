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
        'quantity',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function reference()
    {
        return $this->belongsTo(Preorder::class, 'reference_id');
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
