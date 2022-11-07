<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Order extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'orders';
    public const BULAN_ROMAWI = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'no_order',
        'date',
        'salesperson_id',
        'semester_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appended = [
        'lunas',
        'selesai'
    ];

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function salesperson()
    {
        return $this->belongsTo(Salesperson::class, 'salesperson_id');
    }

    public function kotasale()
    {
        return $this->belongsTo(KotaSale::class, 'kota_sales_id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function tagihan()
    {
        return $this->hasOne(Tagihan::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function generateNoOrder($semester)
    {
        $data = self::where('semester_id', $semester)->count();
        $semester = Semester::find($semester);

        $order_number = !$data ? 1 : ($data + 1);

        $prefix = 'ORD/'.$semester->tipe. '/MMJ/'.ORDER::BULAN_ROMAWI[Date::now()->format('n')].'/'.Date::now()->format('y').'/';
        $code = $prefix.sprintf("%03d", $order_number);

        return $code;
    }

    public function getSisaTagihanAttribute()
    {
        if ($this->relationLoaded('pembayarans') && $this->relationLoaded('invoices')) {
            return $this->invoices->sum('nominal') - $this->pembayarans->sum('nominal');
        }

        return $this->invoices()->sum('nominal') - $this->pembayarans()->sum('nominal');
    }

    public function getLunasAttribute() {
        if ((float) $this->tagihan->tagihan <= (float) $this->tagihan->saldo) {
            return true;
        }
        return false;
    }

    public function getSelesaiAttribute() {
        if ((float) $this->tagihan->total <= (float) $this->tagihan->tagihan) {
            return true;
        }
        return false;
    }
}
