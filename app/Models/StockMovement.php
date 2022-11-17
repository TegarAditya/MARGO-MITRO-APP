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
        'adjustment' => 'Adjustment',
        'invoice' => 'Invoice',
        'realisasi' => 'Realisasi',
        'kelengkapan' => 'Kelengkapan',
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
        'stock_awal',
        'stock_akhir',
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
        if ($this->type === 'invoice') {
            return $this->belongsTo(Invoice::class, 'reference');
        } else if ($this->type === 'adjustment') {
            return $this->belongsTo(StockAdjustment::class, 'reference');
        } else if ($this->type === 'realisasi') {
            return $this->belongsTo(Realisasi::class, 'reference');
        } else if ($this->type === 'kelengkapan') {
            return $this->belongsTo(Invoice::class, 'reference');
        }
    }

}
