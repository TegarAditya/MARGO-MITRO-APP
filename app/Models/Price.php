<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'prices';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function details()
    {
        return $this->hasMany(PriceDetail::class, 'price_id');
    }

    public function getNamaHargaAttribute() {
        $nama = $this->name;
        if ($this->category) {
            $nama .= ' - HAL '. $this->category->name;
        }
        // $nama .= ' - '. $this->price;
        return $nama;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
