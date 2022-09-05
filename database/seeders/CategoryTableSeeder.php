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
                'name'  => 'BAHAN',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'BAHAN'),
            ]
        ];
        $subcats = [
            [
                'name'  => 'SD',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SD'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'MI',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'MI'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMP',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMP'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'MTS',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'MTS'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMA',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMA'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMA PEMINATAN',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMA PEMINATAN'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'MA',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'MA'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMK',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMK'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'SD MERDEKA',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SD MERDEKA'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMP MERDEKA',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMP MERDEKA'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => 'SMA MERDEKA',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'SMA MERDEKA'),
                'type'  => 'jenjang',
                'parent_id' => 1,
            ],
            [
                'name'  => '1',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '1'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '2',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '2'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '3',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '3'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '4',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '4'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '5',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '5'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '6',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '6'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '7',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '7'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '8',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '8'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '9',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '9'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '10',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '10'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '11',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '11'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '12',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '12'),
                'type'  => 'kelas',
                'parent_id' => 1,
            ],
            [
                'name'  => '48',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '48'),
                'type'  => 'halaman',
                'parent_id' => 1,
            ],
            [
                'name'  => '64',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '64'),
                'type'  => 'halaman',
                'parent_id' => 1,
            ],
            [
                'name'  => '96',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '96'),
                'type'  => 'halaman',
                'parent_id' => 1,
            ],
            [
                'name'  => '112',
                'slug'  => SlugService::createSlug(Category::class, 'slug', '112'),
                'type'  => 'halaman',
                'parent_id' => 1,
            ],
            [
                'name'  => 'PG',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'PG'),
                'type'  => 'halaman',
                'parent_id' => 1,
            ],
            [
                'name'  => 'MERDEKA',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'MERDEKA'),
                'type'  => 'isi',
                'parent_id' => 1,
            ],
            [
                'name'  => 'K13',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'K13'),
                'type'  => 'isi',
                'parent_id' => 1,
            ],
            [
                'name'  => 'KUR BERLAKU',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'KUR BERLAKU'),
                'type'  => 'isi',
                'parent_id' => 1,
            ],
            [
                'name'  => 'MGMP(Naskah Sendiri)',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'MGMP(Naskah Sendiri)'),
                'type'  => 'isi',
                'parent_id' => 1,
            ],
            [
                'name'  => 'KERTAS',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'KERTAS'),
                'type'  => 'bahan',
                'parent_id' => 2,
            ],
            [
                'name'  => 'PLATE',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'PLATE'),
                'type'  => 'bahan',
                'parent_id' => 2,
            ],
            [
                'name'  => 'CHECMICAL FLUID',
                'slug'  => SlugService::createSlug(Category::class, 'slug', 'CHECMICAL FLUID'),
                'type'  => 'bahan',
                'parent_id' => 2,
            ],
        ];

        Category::insert($cats);
        Category::insert($subcats);
    }
}
