<?php

namespace App\Exports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportSisaOrdersExport implements FromCollection
{
    use Exportable;

    private Collection $orders;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        $collections = collect([]);
        $rows = collect([]);

        $rows->push([
            'no' => 'No.',
            'no_order' => 'No. Order',
            'date' => 'Tanggal',
            'salesperson' => 'Sales',
            'product_jenjang' => 'Jenjang',
            'product_kelas' => 'kelas',
            'product_judul' => 'Tema/Mapel',
            'product_hal' => 'Hal',
            'product_qty' => 'Pesanan',
            'product_move' => 'Dikirim',
            'product_sisa' => 'Sisa',
            'product_qty_pg' => 'Pesanan(PG)',
            'product_move_pg' => 'Dikirim(PG)',
            'product_sisa_pg' => 'Sisa(PG)',
        ]);

        foreach ($this->orders as $order) {
            $salesperson = $order->salesperson;

            $order_details = $order->order_details;

            $sorted = $order_details->sortBy('product.kelas_id')
                ->sortBy('product.tiga_nama')->sortBy('product.jenjang_id');
            $details = $sorted->values()->all();

            foreach ($details as $detail) {
                $product = $detail->product;
                $bonus = $detail->bonus;

                if (!$product) {
                    continue;
                }

                if ($detail->quantity < $detail->moved) {
                    continue;
                }

                $row = [
                    'no_order' => $order->no_order,
                    'date' => $order->date,
                    'salesperson' => $salesperson->name,
                    'product_jenjang_id' => $product->jenjang_id,
                    'product_jenjang_name' => $product->jenjang->name ?? '-',
                    'product_kelas' => (string) $product->kelas->name,
                    'product_judul' => $product->name,
                    'product_hal' => $product->halaman->name,
                    'product_qty' => (string) $detail->quantity,
                    'product_move' => (string) $detail->moved,
                    'product_sisa' => (string) ($detail->quantity - $detail->moved),
                    'product_qty_pg' => (string) $bonus ? $bonus->quantity : '-',
                    'product_move_pg' => (string) $bonus ? $bonus->moved : '-',
                    'product_sisa_pg' => (string) $bonus ? ($bonus->quantity - $bonus->moved) : '-',
                ];

                $collections->push($row);
            }
        }

        $groups = $collections->groupBy('product_jenjang_name')->sortBy('product_jenjang_id');
        $i = 0;

        foreach($groups as $key => $value) {
            $rows->push([
                'no' => '',
                'no_order' => $key,
                'date' => '',
                'salesperson' => '',
                'product_jenjang' => $key,
                'product_kelas' => '',
                'product_judul' => '',
                'product_hal' => '',
                'product_qty' => '',
                'product_move' => '',
                'product_sisa' => '',
                'product_qty_pg' => '',
                'product_move_pg' => '',
                'product_sisa_pg' => '',
            ]);

            foreach($value as $collection) {
                $i++;
                $rows->push([
                    'no' => $i,
                    'no_order' => $collection['no_order'],
                    'salesperson' => $collection['salesperson'],
                    'date' => $collection['date'],
                    'product_jenjang' => $collection['product_jenjang_name'],
                    'product_kelas' => $collection['product_kelas'],
                    'product_judul' => $collection['product_judul'],
                    'product_hal' => (string) $collection['product_hal'],
                    'product_qty' => (string) $collection['product_qty'],
                    'product_move' => (string) $collection['product_move'],
                    'product_sisa' => (string) $collection['product_sisa'],
                    'product_qty_pg' => (string) $collection['product_qty_pg'],
                    'product_move_pg' => (string) $collection['product_move_pg'],
                    'product_sisa_pg' => (string) $collection['product_sisa_pg'],
                ]);
            }
        }

        return $rows;
    }
}
