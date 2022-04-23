<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagihanMovement extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const TYPE_SELECT = [
        'faktur'     => 'Faktur',
        'pembayaran' => 'Pembayaran',
    ];

    public $table = 'tagihan_movements';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'tagihan_id',
        'reference',
        'type',
        'nominal',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
