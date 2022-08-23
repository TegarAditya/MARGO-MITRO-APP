<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class NewContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $permissions = [
        //     [
        //         'title' => 'custom_price_create',
        //     ],
        //     [
        //         'title' => 'custom_price_edit',
        //     ],
        //     [
        //         'title' => 'custom_price_show',
        //     ],
        //     [
        //         'title' => 'custom_price_delete',
        //     ],
        //     [
        //         'title' => 'custom_price_access',
        //     ],
        // ];

        $permissions = [
            [
                'title' => 'semester_create',
            ],
            [
                'title' => 'semester_edit',
            ],
            [
                'title' => 'semester_show',
            ],
            [
                'title' => 'semester_delete',
            ],
            [
                'title' => 'semester_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
