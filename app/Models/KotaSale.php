<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaSale extends Model
{
    use HasFactory;

    public $table = 'kota_sales';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'sales_id',
        'kota_id',
        'name',
        'created_at',
        'updated_at',
    ];

    public function sales()
    {
        return $this->belongsTo(Salesperson::class, 'sales_id');
    }

    public function kota()
    {
        return $this->belongsTo(City::class, 'kota_id');
    }

    public function alamats()
    {
        return $this->hasMany(AlamatSale::class, 'kota_sales_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
