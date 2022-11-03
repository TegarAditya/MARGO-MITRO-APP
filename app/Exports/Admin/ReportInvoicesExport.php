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
            'no_order' => 'No. Order',
            'no_invoice' => 'No. Invoice',
            'no_suratjalan' => 'No. Surat Jalan',
            'salesperson' => 'Sales Person',
            'date' => 'Tanggal',
            'type' => 'Jenis',
            'product_jenjang' => 'Jenjang',
            'product_kelas' => 'kelas',
            'product_judul' => 'Tema/Mapel',
            'product_hal' => 'Hal',
            'product_name' => 'Nama Produk',
            'product_price' => 'Harga Produk',
            'product_qty' => 'Quantity',
            'product_qty_pg' => 'Kelengkapan',
            'product_subtotal' => 'Subtotal',
            'nominal' => 'Total',
        ]);

        $i = 0;
        foreach ($this->invoices as $invoice) {
            $order = $invoice->order;
            $salesperson = $order->salesperson;
            $is_out = 0 < $invoice->nominal;

            $invoice_details = $invoice->invoice_details;

            $sorted = $invoice_details->sortBy('product.kelas_id')
                ->sortBy('product.tiga_nama')->sortBy('product.jenjang_id');
            $details = $sorted->values()->all();


            foreach ($details as $invoice_detail) {
                $i++;
                $product = $invoice_detail->product;

                $row = [
                    'no' => $i,
                    'no_order' => $order->no_order,
                    'no_invoice' => $invoice->no_invoice,
                    'no_suratjalan' => $invoice->no_suratjalan,
                    'salesperson' => $salesperson->name,
                    'date' => $invoice->date,
                    'type' => $is_out ? 'Faktur' : 'Faktur Retur',
                    'product_jenjang' => $product->jenjang->name,
                    'product_kelas' => (string) $product->kelas->name,
                    'product_judul' => $product->name,
                    'product_hal' => $product->halaman->name,
                    'product_name' => $product->nama_isi_buku,
                    'product_price' => $invoice_detail->price,
                    'product_qty' => abs($invoice_detail->quantity),
                    'product_qty_pg' => $invoice_detail->bonus ? abs($invoice_detail->bonus->quantity) : '',
                    'product_subtotal' => abs($invoice_detail->total),
                    'nominal' => abs($invoice->nominal),
                ];

                $rows->push($row);
            }
        }

        return $rows;
    }
}
