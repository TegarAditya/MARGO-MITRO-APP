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
        $permissions = [
            [
                'id'    => 114,
                'title' => 'custom_price_create',
            ],
            [
                'id'    => 115,
                'title' => 'custom_price_edit',
            ],
            [
                'id'    => 116,
                'title' => 'custom_price_show',
            ],
            [
                'id'    => 117,
                'title' => 'custom_price_delete',
            ],
            [
                'id'    => 118,
                'title' => 'custom_price_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
