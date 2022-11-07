<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public const OPERATION_SELECT = [
        'add'       => 'Tambah',
        'delete'    => 'Hapus',
        'defective' => 'Rusak',
        'lost'      => 'Hilang',
    ];

    public $table = 'stock_adjustments';

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'date',
        'operation',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'is_increase',
    ];

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

    public function getIsIncreaseAttribute()
    {
        if (in_array($this->operation, array('add'))) {
            return true;
        } else {
            return false;
        }
    }

    public function details()
    {
        return $this->hasMany(StockAdjustmentDetail::class);
    }
}
