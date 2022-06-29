<?php

namespace App\Exports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportInvoicesExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private Collection $invoices;

    public function __construct(Collection $invoices)
    {
        $this->invoices = $invoices;
    }

    public function collection()
    {
        $rows = collect([]);

        $rows->push([
            'no' => 'No.',
            'id' => 'ID',
            'no_order' => 'No. Order',
            'no_invoice' => 'No. Invoice',
            'no_suratjalan' => 'No. Surat Jalan',
            'salesperson' => 'Sales Person',
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
        foreach ($this->invoices as $invoice) {
            $order = $invoice->order;
            $salesperson = $order->salesperson;
            $is_out = 0 < $invoice->nominal;

            foreach ($invoice->invoice_details as $invoice_detail) {
                $i++;
                $product = $invoice_detail->product;

                $row = [
                    'no' => $i,
                    'id' => $invoice->id,
                    'no_order' => $order->no_order,
                    'no_invoice' => $invoice->no_invoice,
                    'no_suratjalan' => $invoice->no_suratjalan,
                    'salesperson' => $salesperson->name,
                    'date' => $invoice->date,
                    'type' => $is_out ? 'Keluar' : 'Masuk',
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $invoice_detail->price,
                    'product_qty' => abs($invoice_detail->quantity),
                    'product_subtotal' => abs($invoice_detail->total),
                    'nominal' => abs($invoice->nominal),
                ];

                $rows->push($row);
            }
        }

        return $rows;
    }
}
