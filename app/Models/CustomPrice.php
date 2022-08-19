<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomPrice extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'custom_prices';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nama',
        'kategori_id',
        'harga',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'harga' => 'double',
    ];

    public function kategori()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
