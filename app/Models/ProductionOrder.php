<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionOrder extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'production_orders';

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'po_number',
        'no_spk',
        'no_kwitansi',
        'productionperson_id',
        'date',
        'total',
        'bayar',
        'created_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'total' => 'double',
        'bayar' => 'double',
    ];

    public function productionperson()
    {
        return $this->belongsTo(Productionperson::class, 'productionperson_id');
    }

    public function production_order_details()
    {
        return $this->hasMany(ProductionOrderDetail::class);
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
