<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatSale extends Model
{
    use HasFactory;

    public $table = 'alamat_sales';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'kota_sales_id',
        'alamat',
        'created_at',
        'updated_at',
    ];

    public function kota_sales()
    {
        return $this->belongsTo(KotaSale::class, 'kota_sales_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
