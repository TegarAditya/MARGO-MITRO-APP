<?php

namespace App\Exports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportOrdersExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private Collection $orders;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        $rows = collect([]);

        $rows->push([
            'no' => 'No.',
            'no_order' => 'No. Order',
            'salesperson' => 'Sales Person',
            'date' => 'Tanggal',
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

        $i = 0;
        foreach ($this->orders as $order) {
            $salesperson = $order->salesperson;

            $order_details = $order->order_details;

            $sorted = $order_details->sortBy('product.kelas_id')
                ->sortBy('product.tiga_nama')->sortBy('product.jenjang_id');
            $details = $sorted->values()->all();

            foreach ($details as $detail) {
                $i++;
                $product = $detail->product;
                $bonus = $detail->bonus;

                $row = [
                    'no' => $i,
                    'no_order' => $order->no_order,
                    'salesperson' => $salesperson->name,
                    'date' => $order->date,
                    'product_jenjang' => $product->jenjang->name,
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

                $rows->push($row);
            }
        }

        return $rows;
    }
}
