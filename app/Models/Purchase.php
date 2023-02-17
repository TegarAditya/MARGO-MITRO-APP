<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Purchase extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'purchases';

    public const BULAN_ROMAWI = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'no_suratjalan',
        'no_spk',
        'date',
        'note',
        'productionperson_id',
        'semester_id',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public static function generateNoSpk($semester) {
        $data = self::where('semester_id', $semester)->count();
        $semester = Semester::find($semester);

        $number = !$data ? 1 : ($data + 1);

        $prefix = 'IN/'.$semester->tipe.'/MMJ/'.Purchase::BULAN_ROMAWI[Date::now()->format('n')].'/'.Date::now()->format('y').'/';
        $code = $prefix.sprintf("%03d", $number);

        return $code;
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function subkontraktor()
    {
        return $this->belongsTo(Productionperson::class, 'productionperson_id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
