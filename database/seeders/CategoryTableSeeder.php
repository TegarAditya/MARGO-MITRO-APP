<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = [
            [
                'name'  => 'BUKU',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'BUKU'),
            ],
            [
                'name'  => 'KERTAS',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'KERTAS'),
            ],
            [
                'name'  => 'BAHAN PEMBANTU',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'BAHAN PEMBANTU'),
            ]
        ];
        $subcats = [
            [
                'name'  => 'SD K13',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SD K13'),
                'parent_id' => 1,
            ],
            [
                'name'  => 'MI K13',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'MI K13'),
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMP K13',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMP K13'),
                'parent_id' => 1,
            ],
            [
                'name'  => 'MTS K13',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'MTS K13'),
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMA K13',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMA K13'),
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMK K13',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMK K13'),
                'parent_id' => 1,
            ],
            [
                'name'  => 'PLATE',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'PLATE'),
                'parent_id' => 3,
            ],
            [
                'name'  => 'CHECMICAL FLUID',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'CHECMICAL FLUID'),
                'parent_id' => 3,
            ],
        ];

        Category::insert($cats);
        Category::insert($subcats);
    }
}
