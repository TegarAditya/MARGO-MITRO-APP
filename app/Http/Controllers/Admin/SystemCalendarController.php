<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\StockAdjustment;
use App\Models\Realisasi;

class SystemCalendarController extends Controller
{
    public $sources = [
        [
            'model'      => '\App\Models\Pembayaran',
            'date_field' => 'tanggal',
            'field'      => 'no_kwitansi',
            'prefix'     => '',
            'suffix'     => '',
            'route'      => 'admin.pembayarans.edit',
        ],
        [
            'model'      => '\App\Models\Invoice',
            'date_field' => 'date',
            'field'      => 'no_invoice',
            'prefix'     => 'Invoice',
            'suffix'     => 'Terbit',
            'route'      => 'admin.invoices.edit',
        ],
    ];

    public function index()
    {
        $events = [];

        $invoices = Invoice::with(['order'])->get();
        foreach($invoices as $invoice) {
            $events[] = [
                'title' => 'Faktur '. (0 < $invoice->nominal ? 'Return ' : '').'- No Invoice: '. $invoice->no_invoice . ' Sejumlah '. formatCurrency(abs($invoice->nominal), 'IDR') .' Untuk '. $invoice->order->salesperson->name,
                'start' => $invoice->date,
                'url' => route('admin.orders.show', $invoice->order->id)
            ];
        }

        $pembayarans = Pembayaran::with(['order'])->get();
        foreach($pembayarans as $row) {
            $events[] = [
                'title' => 'Pembayaran - No Kwitansi: '. $row->no_kwitansi . ' Sejumlah '. formatCurrency($row->nominal, 'IDR') .' Diterima dari '. $row->order->salesperson->name,
                'start' => $row->tanggal,
                'url' => route('admin.orders.show', $row->order->id)
            ];
        }

        $stock_movements = StockAdjustment::with(['product'])->get();
        foreach($stock_movements as $row) {
            $events[] = [
                'title' => 'Stock Adjustment - '. StockAdjustment::OPERATION_SELECT[$row->operation]. ' Stock Barang : "'. $row->product->name . '" Sejumlah '. $row->quantity .' '. $row->product->unit->name,
                'start' => $row->date,
                'url' => route('admin.stock-adjustments.show', $row->id)
            ];
        }

        $realisasis = Realisasi::with(['production_order'])->get();
        foreach($realisasis as $row) {
            $events[] = [
                'title' => 'Realisasi Finishing - No SPK: '. $row->production_order->no_spk .' Subkontraktor:  '. $row->production_order->productionperson->name. ' Dengan No Realisasi: '. $row->no_realisasi.' Sejumlah '. formatCurrency($row->nominal, 'IDR'),
                'start' => $row->date,
                'url' => route('admin.production-orders.show', $row->production_order->id)
            ];
        }


        // foreach ($this->sources as $source) {
        //     foreach ($source['model']::all() as $model) {
        //         $crudFieldValue = $model->getAttributes()[$source['date_field']];

        //         if (!$crudFieldValue) {
        //             continue;
        //         }

        //         $events[] = [
        //             'title' => trim($source['prefix'] . ' ' . $model->{$source['field']} . ' ' . $source['suffix']),
        //             'start' => $crudFieldValue,
        //             'url'   => route($source['route'], $model->id),
        //         ];
        //     }
        // }

        return view('admin.calendar.calendar', compact('events'));
    }
}
