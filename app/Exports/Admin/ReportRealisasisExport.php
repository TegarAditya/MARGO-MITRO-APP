<?php

namespace App\Exports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportRealisasisExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private Collection $realisasis;

    public function __construct(Collection $realisasis)
    {
        $this->realisasis = $realisasis;
    }

    public function collection()
    {
        $rows = collect([]);

        $rows->push([
            'no' => 'No.',
            'id' => 'ID',
            'po_number' => 'No. Production Order',
            'no_spk' => 'No. SPK',
            'no_kwitansi' => 'No. Kwitansi',
            'productionperson' => 'Production Person',
            'date' => 'Tanggal',
            'type' => 'Jenis',
            'product_id' => 'ID Produk',
            'product_name' => 'Nama Produk',
            'product_price' => 'Harga Produk',
            'product_qty' => 'Qty Produk',
            'product_subtotal' => 'Subtotal',
            'nominal' => 'Total',
        ]);

        $i = 0;
        foreach ($this->realisasis as $realisasi) {
            $productionOrder = $realisasi->production_order;
            $productionperson = $productionOrder->productionperson;

            foreach ($realisasi->realisasi_details as $realisasi_detail) {
                $i++;
                $product = $realisasi_detail->product;

                $row = [
                    'no' => $i,
                    'id' => $realisasi->id,
                    'po_number' => $productionOrder->po_number,
                    'no_spk' => $productionOrder->no_spk,
                    'no_kwitansi' => $productionOrder->no_kwitansi,
                    'productionperson' => $productionperson->name,
                    'date' => $realisasi->date,
                    'type' => ucfirst($productionOrder->type),
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $realisasi_detail->price,
                    'product_qty' => $realisasi_detail->qty,
                    'product_subtotal' => abs($realisasi_detail->total),
                    'nominal' => abs($realisasi->nominal),
                ];

                $rows->push($row);
            }
        }

        return $rows;
    }
}
