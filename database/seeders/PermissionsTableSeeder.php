<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 20,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 21,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => 22,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => 23,
                'title' => 'master_access',
            ],
            [
                'id'    => 24,
                'title' => 'unit_create',
            ],
            [
                'id'    => 25,
                'title' => 'unit_edit',
            ],
            [
                'id'    => 26,
                'title' => 'unit_show',
            ],
            [
                'id'    => 27,
                'title' => 'unit_delete',
            ],
            [
                'id'    => 28,
                'title' => 'unit_access',
            ],
            [
                'id'    => 29,
                'title' => 'brand_create',
            ],
            [
                'id'    => 30,
                'title' => 'brand_edit',
            ],
            [
                'id'    => 31,
                'title' => 'brand_show',
            ],
            [
                'id'    => 32,
                'title' => 'brand_delete',
            ],
            [
                'id'    => 33,
                'title' => 'brand_access',
            ],
            [
                'id'    => 34,
                'title' => 'city_create',
            ],
            [
                'id'    => 35,
                'title' => 'city_edit',
            ],
            [
                'id'    => 36,
                'title' => 'city_show',
            ],
            [
                'id'    => 37,
                'title' => 'city_delete',
            ],
            [
                'id'    => 38,
                'title' => 'city_access',
            ],
            [
                'id'    => 39,
                'title' => 'category_create',
            ],
            [
                'id'    => 40,
                'title' => 'category_edit',
            ],
            [
                'id'    => 41,
                'title' => 'category_show',
            ],
            [
                'id'    => 42,
                'title' => 'category_delete',
            ],
            [
                'id'    => 43,
                'title' => 'category_access',
            ],
            [
                'id'    => 44,
                'title' => 'product_create',
            ],
            [
                'id'    => 45,
                'title' => 'product_edit',
            ],
            [
                'id'    => 46,
                'title' => 'product_show',
            ],
            [
                'id'    => 47,
                'title' => 'product_delete',
            ],
            [
                'id'    => 48,
                'title' => 'product_access',
            ],
            [
                'id'    => 49,
                'title' => 'salesperson_create',
            ],
            [
                'id'    => 50,
                'title' => 'salesperson_edit',
            ],
            [
                'id'    => 51,
                'title' => 'salesperson_show',
            ],
            [
                'id'    => 52,
                'title' => 'salesperson_delete',
            ],
            [
                'id'    => 53,
                'title' => 'salesperson_access',
            ],
            [
                'id'    => 54,
                'title' => 'order_create',
            ],
            [
                'id'    => 55,
                'title' => 'order_edit',
            ],
            [
                'id'    => 56,
                'title' => 'order_show',
            ],
            [
                'id'    => 57,
                'title' => 'order_delete',
            ],
            [
                'id'    => 58,
                'title' => 'order_access',
            ],
            [
                'id'    => 59,
                'title' => 'order_detail_create',
            ],
            [
                'id'    => 60,
                'title' => 'order_detail_access',
            ],
            [
                'id'    => 61,
                'title' => 'invoice_create',
            ],
            [
                'id'    => 62,
                'title' => 'invoice_edit',
            ],
            [
                'id'    => 63,
                'title' => 'invoice_show',
            ],
            [
                'id'    => 64,
                'title' => 'invoice_delete',
            ],
            [
                'id'    => 65,
                'title' => 'invoice_access',
            ],
            [
                'id'    => 66,
                'title' => 'invoice_detail_create',
            ],
            [
                'id'    => 67,
                'title' => 'invoice_detail_access',
            ],
            [
                'id'    => 68,
                'title' => 'stock_adjustment_create',
            ],
            [
                'id'    => 69,
                'title' => 'stock_adjustment_edit',
            ],
            [
                'id'    => 70,
                'title' => 'stock_adjustment_show',
            ],
            [
                'id'    => 71,
                'title' => 'stock_adjustment_delete',
            ],
            [
                'id'    => 72,
                'title' => 'stock_adjustment_access',
            ],
            [
                'id'    => 73,
                'title' => 'stock_movement_create',
            ],
            [
                'id'    => 74,
                'title' => 'stock_movement_access',
            ],
            [
                'id'    => 75,
                'title' => 'tagihan_create',
            ],
            [
                'id'    => 76,
                'title' => 'tagihan_edit',
            ],
            [
                'id'    => 77,
                'title' => 'tagihan_show',
            ],
            [
                'id'    => 78,
                'title' => 'tagihan_delete',
            ],
            [
                'id'    => 79,
                'title' => 'tagihan_access',
            ],
            [
                'id'    => 80,
                'title' => 'tagihan_movement_show',
            ],
            [
                'id'    => 81,
                'title' => 'tagihan_movement_access',
            ],
            [
                'id'    => 82,
                'title' => 'pembayaran_create',
            ],
            [
                'id'    => 83,
                'title' => 'pembayaran_edit',
            ],
            [
                'id'    => 84,
                'title' => 'pembayaran_show',
            ],
            [
                'id'    => 85,
                'title' => 'pembayaran_delete',
            ],
            [
                'id'    => 86,
                'title' => 'pembayaran_access',
            ],
            [
                'id'    => 87,
                'title' => 'stock_access',
            ],
            [
                'id'    => 88,
                'title' => 'stock_opname_create',
            ],
            [
                'id'    => 89,
                'title' => 'stock_opname_edit',
            ],
            [
                'id'    => 90,
                'title' => 'stock_opname_show',
            ],
            [
                'id'    => 91,
                'title' => 'stock_opname_delete',
            ],
            [
                'id'    => 92,
                'title' => 'stock_opname_access',
            ],
            [
                'id'    => 93,
                'title' => 'sales_order_access',
            ],
            [
                'id'    => 94,
                'title' => 'invoice_menu_access',
            ],
            [
                'id'    => 95,
                'title' => 'tagihan_menu_access',
            ],
            [
                'id'    => 96,
                'title' => 'pembayaran_menu_access',
            ],
            [
                'id'    => 97,
                'title' => 'productionperson_create',
            ],
            [
                'id'    => 98,
                'title' => 'productionperson_edit',
            ],
            [
                'id'    => 99,
                'title' => 'productionperson_show',
            ],
            [
                'id'    => 100,
                'title' => 'productionperson_delete',
            ],
            [
                'id'    => 101,
                'title' => 'productionperson_access',
            ],
            [
                'id'    => 102,
                'title' => 'production_order_create',
            ],
            [
                'id'    => 103,
                'title' => 'production_order_edit',
            ],
            [
                'id'    => 104,
                'title' => 'production_order_show',
            ],
            [
                'id'    => 105,
                'title' => 'production_order_delete',
            ],
            [
                'id'    => 106,
                'title' => 'production_order_access',
            ],
            [
                'id'    => 107,
                'title' => 'production_access',
            ],
            [
                'id'    => 108,
                'title' => 'production_order_detail_create',
            ],
            [
                'id'    => 109,
                'title' => 'production_order_detail_edit',
            ],
            [
                'id'    => 110,
                'title' => 'production_order_detail_show',
            ],
            [
                'id'    => 111,
                'title' => 'production_order_detail_delete',
            ],
            [
                'id'    => 112,
                'title' => 'production_order_detail_access',
            ],
            [
                'id'    => 113,
                'title' => 'profile_password_edit',
            ],
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
            [
                'id'    => 118,
                'title' => 'semester_create',
            ],
            [
                'id'    => 119,
                'title' => 'semester_edit',
            ],
            [
                'id'    => 120,
                'title' => 'semester_show',
            ],
            [
                'id'    => 121,
                'title' => 'semester_delete',
            ],
            [
                'id'    => 122,
                'title' => 'semester_access',
            ],
        ];

        Permission::insert($permissions);
    }
}
