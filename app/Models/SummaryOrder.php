<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SummaryOrder extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const TYPE_SELECT = [
        'isi'   => 'Isi',
        'cover' => 'Cover',
    ];

    public $table = 'summary_orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'type',
        'category_id',
        'product_id',
        'pesanan',
        'stock',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function category()
    {
        if ($this->type === 'cover') {
            return $this->belongsTo(Brand::class, 'category_id');
        } else if ($this->type === 'isi') {
            return $this->belongsTo(Category::class, 'category_id');
        }
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
