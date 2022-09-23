<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceDetail extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'price_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'sales_id',
        'price_id',
        'diskon',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function sales()
    {
        return $this->belongsTo(Salesperson::class, 'sales_id');
    }

    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
