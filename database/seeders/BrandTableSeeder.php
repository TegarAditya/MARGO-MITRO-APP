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
                'name'  => 'MIKA',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MIKA'),
            ],
            [
                'name'  => 'MASTER',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MASTER'),
            ],
            [
                'name'  => 'MGMP KEDIRI',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP KEDIRI'),
            ],
            [
                'name'  => 'MGMP SRAGEN',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'MGMP SRAGEN'),
            ],
            [
                'name'  => 'GEMILANG',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'GEMILANG'),
            ],
            [
                'name'  => 'PELANGI',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'PELANGI'),
            ],
            [
                'name'  => 'PANDAWA',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'PANDAWA'),
            ],
            [
                'name'  => 'SIPINTAR',
                'slug'  => SlugService::createSlug(Brand::class, 'slug', 'SIPINTAR'),
            ],
        ];

        Brand::insert($brands);
    }
}
