<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Preorder extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'preorders';

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'no_preorder',
        'date',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function preorder_details()
    {
        return $this->hasMany(PreorderDetail::class, 'preorder_detail_id');
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function generateNoPO()
    {
        $data = self::whereBetween('created_at', [Date::now()->startOf('month'), Date::now()->endOf('month')])->count();

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'PRE'.Date::now()->format('dm');
        $code = $prefix.sprintf("%04d", $order_number);

        return $code;
    }
}
