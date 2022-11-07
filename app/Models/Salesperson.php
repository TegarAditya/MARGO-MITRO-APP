<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Salesperson extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use Auditable;
    use HasFactory;

    public $table = 'salespeople';

    protected $appends = [
        'foto',
        'nama_sales'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'name',
        'telephone',
        'company',
        'alamat',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function area_pemasarans()
    {
        return $this->belongsToMany(City::class);
    }

    public function getFotoAttribute()
    {
        $file = $this->getMedia('foto')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $number = Salesperson::withTrashed()->max('id') + 1;
            $model->code = 'MKT-'. str_pad($number, 6, '0', STR_PAD_LEFT);
        });
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function kota()
    {
        return $this->hasMany(KotaSale::class, 'sales_id');
    }

    public function getNamaSalesAttribute() {
        $nama = $this->name;
        if (!$this->area_pemasarans->isEmpty()) {
            $nama .= ' (';
            foreach($this->area_pemasarans as $area) {
                if ($this->area_pemasarans->last()) {
                    $nama .= $area->name;
                } else {
                    $nama .= $area->name.', ';
                }
            }
            $nama .= ') ';
        }
        return $nama;
    }
}
