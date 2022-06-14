<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Invoice extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'invoices';

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'no_suratjalan',
        'no_invoice',
        'order_id',
        'date',
        'nominal',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'nominal' => 'double',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetail::class);
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

    public static function generateNoSJ() {
        $data = self::whereBetween('created_at', [Date::now()->startOf('month'), Date::now()->endOf('month')])->count();

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'SJ'.Date::now()->format('dm');
        $code = $prefix.sprintf("%04d", $order_number);

        return $code;
    }

    public static function generateNoInvoice() {
        $data = self::whereBetween('created_at', [Date::now()->startOf('month'), Date::now()->endOf('month')])->count();

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'INV'.Date::now()->format('dm');
        $code = $prefix.sprintf("%04d", $order_number);

        return $code;
    }

    public function getTypeAttribute()
    {
        return $this->nominal > 0 ? 'Keluar' : 'Masuk';
    }
}
