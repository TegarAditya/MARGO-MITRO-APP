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
            'estimasi' => 'Estimasi',
            'pengambilan' => 'Pengambilan',
            'retur' => 'Retur',
            'bayar' => 'Bayar',
            'diskon' => 'Diskon',
            'piutang' => 'Piutang',
        ]);

        $i = 0;
        foreach($this->saldos as $saldo) {
            $i++;

            $estimasi = $saldo->order_details->sum('total');
            $pengambilan = $saldo->invoices->where('nominal', '>', 0)->sum('nominal');
            $retur = abs($saldo->invoices->where('nominal', '<', 0)->sum('nominal'));
            $bayar = $saldo->pembayarans->sum('bayar');
            $diskon = $saldo->pembayarans->sum('diskon');
            $piutang = $pengambilan - ($retur + $bayar + $diskon);

            $row = [
                'no' => $i,
                'sales' => (string) $saldo->name,
                'estimasi' => (string) $estimasi,
                'pengambilan' => (string) $pengambilan,
                'retur' => (string) $retur,
                'bayar' => (string) $bayar,
                'diskon' => (string) $diskon,
                'piutang' => (string) ($piutang),
            ];

            $rows->push($row);
        }

        return $rows;
    }
}
