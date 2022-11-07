<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productionperson extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public const TYPE_SELECT = [
        'percetakan' => 'Percetakan',
        'finishing'  => 'Finishing',
    ];

    public $table = 'productionpeople';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'name',
        'type',
        'contact',
        'alamat',
        'company',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $number = Productionperson::withTrashed()->max('id') + 1;
            $model->code = 'PROD-'. str_pad($number, 6, '0', STR_PAD_LEFT);
        });
    }
}
