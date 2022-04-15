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
        ];

        Brand::insert($brands);
    }
}
