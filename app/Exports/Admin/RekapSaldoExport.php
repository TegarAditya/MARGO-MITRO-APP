<?php

namespace App\Exports\Admin;

use App\Models\Salesperson;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RekapSaldoExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private $saldos;

    public function __construct($saldos)
    {
        $this->saldos = $saldos;
    }
    public function collection()
    {
        $rows = collect([]);

        $rows->push([
            'no' => 'No.',
            'sales' => 'Sales',
            'order' => 'Order',
            'tagihan' => 'Tagihan',
            'pembayaran' => 'Pembayaran',
            'hutang' => 'Hutang',
        ]);

        $i = 0;
        foreach($this->saldos as $saldo) {
            $i++;

            $row = [
                'no' => $i,
                'sales' => (string) $saldo->name,
                'order' => (string) $saldo->pesanan,
                'tagihan' => (string) $saldo->tagihan,
                'pembayaran' => (string) $saldo->bayar,
                'hutang' => (string) ($saldo->tagihan - $saldo->bayar),
            ];

            $rows->push($row);
        }

        return $rows;
    }
}
