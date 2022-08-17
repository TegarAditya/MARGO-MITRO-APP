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
            'product_cover_isi' => 'Cover - Isi',
            'product_nama_buku' => 'Judul',
            'product_jenjang' => 'Jenjang',
            'product_qty' => 'Pesanan',
            'product_move' => 'Dikirim',
            'product_sisa' => 'Sisa',
        ]);

        $i = 0;
        foreach ($this->orders as $order) {
            $salesperson = $order->salesperson;

            foreach ($order->order_details as $detail) {
                $i++;
                $product = $detail->product;

                $row = [
                    'no' => $i,
                    'no_order' => $order->no_order,
                    'salesperson' => $salesperson->name,
                    'date' => $order->date,
                    'product_cover_isi' => '('.$product->brand->name .' - '. $product->isi->name.')',
                    'product_nama_buku' => $product->nama_buku,
                    'product_jenjang' => $product->jenjang->name,
                    'product_qty' => (string) $detail->quantity,
                    'product_move' => (string) $detail->moved,
                    'product_sisa' => (string) ($detail->quantity - $detail->moved)
                ];

                $rows->push($row);
            }
        }

        return $rows;
    }
}
