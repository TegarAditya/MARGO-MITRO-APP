<?php

namespace App\Exports\Admin;

use App\Models\StockOpname;
use App\Models\Brand;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockDetailExport implements FromCollection, ShouldAutoSize
{
    use Exportable;

    private $jenjang;
    private $stocks;
    private $titles;

    public function __construct($jenjang, $stocks, $titles)
    {
        $this->jenjang = $jenjang;
        $this->stocks = $stocks;
        $this->titles = $titles;
    }


    public function collection()
    {
        set_time_limit(0);
        $rows = collect([]);

        $atas = [
            'no' => 'No.',
            'name' => 'Nama Lengkap',
            'isi' => 'Isi',
            'mapel' => 'Mapel/Tema',
            'kelas' => 'Kelas'
        ];

        $covers = Brand::all();

        foreach($covers as $cover) {
            $atas[$cover->slug. 'masuk'] = $cover->name. '  Masuk';
            $atas[$cover->slug. 'keluar'] = $cover->name. '  Keluar';
            $atas[$cover->slug. 'sisa'] = $cover->name. '  Sisa';
            $atas[$cover->slug. 'hpp'] = $cover->name. '  HPP';
            $atas[$cover->slug. 'total'] = $cover->name. '  Total';
        }

        $rows->push($atas);

        $i = 0;
        foreach($this->titles as $title) {
            $i++;
            $row = [
                'no' => $i,
                'name' => $title->nama_buku,
                'isi' => $title->isi->name,
                'mapel' => $title->name,
                'kelas' => $title->kelas->name,
            ];

            foreach($covers as $cover) {
                $result = $this->stocks->where('name', $title->name)->where('isi_id', $title->isi_id)->where('kelas_id', $title->kelas_id)
                        ->where('halaman_id', $title->halaman_id)->where('semester_id', $title->semester_id)->where('brand_id', $cover->id)->first();

                if($result) {
                    $row[$cover->slug. 'masuk'] = (string) $result->masuk ?? 0 ;
                    $row[$cover->slug. 'keluar'] = (string) $result->keluar ? abs($result->keluar) : 0;
                    $row[$cover->slug. 'sisa'] = (string) $result->stock;
                    $row[$cover->slug. 'hpp'] = (string) $result->hpp;
                    $row[$cover->slug. 'total'] = (string) $result->harga_stock;
                } else {
                    $row[$cover->slug. 'masuk'] = '-';
                    $row[$cover->slug. 'keluar'] = '-';
                    $row[$cover->slug. 'sisa'] = '-';
                    $row[$cover->slug. 'hpp'] = '-';
                    $row[$cover->slug. 'total'] = '-';
                }
            }

            $rows->push($row);
        }

        return $rows;
    }
}
