<?php

namespace App\Exports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportPembayaransExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private Collection $pembayarans;

    public function __construct(Collection $pembayarans)
    {
        $this->pembayarans = $pembayarans;
    }

    public function collection()
    {
        $rows = collect([]);

        $rows->push([
            'no' => 'No.',
            'id' => 'ID',
            'no_order' => 'No. Order',
            'no_kwitansi' => 'No. Kwitansi',
            'salesperson' => 'Sales Person',
            'date' => 'Tanggal',
            'nominal' => 'Nominal',
            'diskon' => 'Diskon',
            'bayar' => 'Bayar',
            'tagihan_total' => 'Total Order',
            'tagihan_saldo' => 'Total Pembayaran',
            'tagihan_selisih' => 'Total Tagihan',
        ]);

        $i = 0;
        foreach ($this->pembayarans as $pembayaran) {
            $i++;
            $order = $pembayaran->order;
            $salesperson = $order->salesperson;
            $tagihan = $pembayaran->tagihan;

            $row = [
                'no' => $i,
                'id' => $pembayaran->id,
                'no_order' => $order->no_order,
                'no_kwitansi' => $pembayaran->no_kwitansi,
                'salesperson' => $salesperson->name,
                'date' => $pembayaran->tanggal,
                'nominal' => $pembayaran->nominal,
                'diskon' => $pembayaran->diskon ?: '0',
                'bayar' => $pembayaran->bayar,
                'tagihan_total' => $tagihan->total,
                'tagihan_saldo' => $tagihan->saldo,
                'tagihan_selisih' => $tagihan->selisih ?: '0',
            ];
            
            $rows->push($row);
        }

        return $rows;
    }
}
