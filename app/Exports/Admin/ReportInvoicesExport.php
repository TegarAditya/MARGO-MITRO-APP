<?php

namespace App\Exports\Admin;

use App\Models\Invoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportInvoicesExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private Collection $invoice;

    public function __construct(Collection $invoice)
    {
        $this->invoice = $invoice;
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
            'product_name' => 'Nama Produk',
            'product_price' => 'Harga Produk',
            'product_qty' => 'Qty Produk',
            'product_subtotal' => 'Subtotal',
            'nominal' => 'Total',
        ]);

        $i = 0;
        foreach ($this->invoice as $invoice) {
            $order = $invoice->order;
            $salesperson = $order->salesperson;
            $is_out = 0 < $invoice->nominal;
            $details_count = $invoice->invoice_details->count();

            $ii = 0;
            foreach ($invoice->invoice_details as $invoice_detail) {
                $i++;
                $ii++;
                $product = $invoice_detail->product;

                $row = [
                    'no' => $i,
                    'id' => $ii > 1 ? '' : $invoice->id,
                    'no_order' => $ii > 1 ? '' : $order->no_order,
                    'no_invoice' => $ii > 1 ? '' : $invoice->no_invoice,
                    'no_suratjalan' => $ii > 1 ? '' : $invoice->no_suratjalan,
                    'salesperson' => $ii > 1 ? '' : $salesperson->name,
                    'date' => $ii > 1 ? '' : $invoice->date,
                    'type' => $ii > 1 ? '' : ($is_out ? 'Keluar' : 'Masuk'),
                    'product_name' => $product->name,
                    'product_price' => $invoice_detail->price,
                    'product_qty' => $invoice_detail->quantity,
                    'product_subtotal' => $invoice_detail->total,
                    'nominal' => $ii < $details_count ? '' : $invoice->nominal,
                ];

                $rows->push($row);
            }
        }

        return $rows;
    }
}
