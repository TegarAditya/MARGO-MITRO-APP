<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;
use Cviebrock\EloquentSluggable\Services\SlugService;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = [
            [
                'name'  => 'MMJ',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MMJ'),
            ],
            [
                'name'  => 'MP',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MP'),
            ],
            [
                'name'  => 'AL HUDA',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'AL HUDA'),
            ],
            [
                'name'  => 'FATONAH',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'FATONAH'),
            ],
            [
                'name'  => 'ANNUR',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'ANNUR'),
            ],
            [
                'name'  => 'CILACAP PARYANTO',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'CILACAP PARYANTO'),
            ],
            [
                'name'  => 'BANYUMAS',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'BANYUMAS'),
            ],
            [
                'name'  => 'JUARA',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'JUARA'),
            ],
            [
                'name'  => 'BRILIANT',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'BRILIANT'),
            ],
            [
                'name'  => 'GEMILANG',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'GEMILANG'),
            ],
            [
                'name'  => 'PANDAWA',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'PANDAWA'),
            ],
            [
                'name'  => 'LAMONGAN',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'LAMONGAN'),
            ],
            [
                'name'  => 'PELANGI',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'PELANGI'),
            ],
            [
                'name'  => 'KRESNA',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'KRESNA'),
            ],
            [
                'name'  => 'APIN MAS',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'APIN MAS'),
            ],
            [
                'name'  => 'MGMP SRAGEN',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP SRAGEN'),
            ],
            [
                'name'  => 'SI PINTAR',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'SI PINTAR'),
            ],
            [
                'name'  => 'MGMP PARYANTO',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP PARYANTO'),
            ],
            [
                'name'  => 'MIKA',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MIKA'),
            ],
            [
                'name'  => 'MGMP TUBAN',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP TUBAN'),
            ],
            [
                'name'  => 'MGMP KEDIRI',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP KEDIRI'),
            ],
            [
                'name'  => 'MGMP MAGETAN',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP MAGETAN'),
            ],
            [
                'name'  => 'MGMP TRENGGALEK',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP TRENGGALEK'),
            ],
            [
                'name'  => 'SPORTIF',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'SPORTIF'),
            ],
            [
                'name'  => 'MGMP CILACAP',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP CILACAP'),
            ],
            [
                'name'  => 'MGMP JEPARA',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP JEPARA'),
            ],
            [
                'name'  => 'NU KENDAL',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'NU KENDAL'),
            ],
            [
                'name'  => 'MGMP SOLO',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP SOLO'),
            ],
            [
                'name'  => 'MGMP JAMBI',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP JAMBI'),
            ],
            [
                'name'  => 'MGMP GROBOGAN',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP GROBOGAN'),
            ],
            [
                'name'  => 'MASTER',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MASTER'),
            ],
        ];

        Brand::insert($brands);
    }
}
