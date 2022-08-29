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
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use Auditable;
    use HasFactory;
    use Sluggable;

    public const TIPE_PG_SELECT = [
        'pg'     => 'PG',
        'kunci'  => 'KUNCI',
        'non_pg' => 'NON PG',
    ];

    public $table = 'products';

    protected $appends = [
        'foto',
        'nama_buku'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'brand_id',
        'unit_id',
        'jenjang_id',
        'kelas_id',
        'halaman_id',
        'isi_id',
        'tipe_pg',
        'pg_id',
        'kunci_id',
        'hpp',
        'price',
        'stock',
        'finishing_cost',
        'min_stock',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'hpp' => 'double',
        'price' => 'double',
        'finishing_cost' => 'double',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function jenjang()
    {
        return $this->belongsTo(Category::class, 'jenjang_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Category::class, 'kelas_id');
    }

    public function halaman()
    {
        return $this->belongsTo(Category::class, 'halaman_id');
    }

    public function isi()
    {
        return $this->belongsTo(Category::class, 'isi_id');
    }

    public function pg()
    {
        return $this->belongsTo(Product::class, 'pg_id');
    }

    public function kunci()
    {
        return $this->belongsTo(Product::class, 'kunci_id');
    }

    public function stock_movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getFotoAttribute()
    {
        $files = $this->getMedia('foto');
        $files->each(function ($item) {
            $item->url = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview = $item->getUrl('preview');
        });

        return $files;
    }

    public function getNamaBukuAttribute()
    {
        $nama = $this->name;
        if ($this->kelas) {
            $nama .= ' -  KELAS '. $this->kelas->name;
        }
        if ($this->halaman) {
            $nama .= ' -  HAL '. $this->halaman->name;
        }

        return $nama;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
