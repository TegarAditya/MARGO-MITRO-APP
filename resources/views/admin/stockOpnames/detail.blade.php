@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-warning" href="{{ route('admin.stock-opnames.export', ['jenjang' => $jenjang->id, 'pg' => $pg, 'semester' => $semester->id]) }}">
            Export Stock
        </a>
    </div>
</div>
@php
    set_time_limit(0);
@endphp
<div class="card">
    <div class="card-header">
        Laporan Stock {{ ucwords($pg) }} Jenjang {{ $jenjang->name }} {{ $semester->name }} Tanggal {{ $tanggal }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable-stock">
                <thead>
                    <tr>
                        <th rowspan="2" width="10"></th>
                        <th rowspan="2">Isi</th>
                        <th rowspan="2" style="min-width: 300px">
                            Tema/Mapel
                        </th>
                        @foreach ($covers as $cover)
                            <th colspan="7">
                                {{ $cover->name }}
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($covers as $cover)
                            <th>

                            </th>
                            <th>
                                Stock Awal
                            </th>
                            <th>
                                Masuk
                            </th>
                            <th>
                                Keluar
                            </th>
                            <th>
                                Stock Akhir
                            </th>
                            {{-- <th>
                                Stock Real
                            </th> --}}
                            <th>
                                HPP
                            </th>
                            <th style="min-width: 100px">
                                Total
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($title as $item)
                        <tr>
                            <td></td>
                            <td>{{ $item->isi->name }}</td>
                            <td>
                                {{ $item->nama_buku }}
                            </td>
                            @foreach ($covers as $cover)
                                @php
                                    $result = $products->where('name', $item->name)->where('isi_id', $item->isi_id)->where('kelas_id', $item->kelas_id)
                                        ->where('halaman_id', $item->halaman_id)->where('semester_id', $item->semester_id)->where('brand_id', $cover->id)->first();
                                @endphp
                                @if($result)
                                    @php
                                        if ($result->stock_movements->count() > 0) {
                                            $stock_akhir = $result->stock_movements->first()->stock_akhir;
                                            $stock_awal = $result->stock_movements->sortBy('id')->first()->stock_awal;
                                        } else {
                                            $stock_akhir = 0;
                                            $stock_awal = 0;
                                        }
                                    @endphp
                                    <td>
                                        <a href="{{ route('admin.buku.show', $result->id) }}"><i class="fas fa-eye text-success  fa-lg"></i></a>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($stock_awal,0,",",".") }}
                                    </td>
                                    <td class="text-center">
                                        {{ $result->masuk ? number_format($result->masuk,0,",",".") : 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $result->keluar ?  number_format(abs($result->keluar),0,",",".") : 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($stock_akhir,0,",",".") }}
                                    </td>
                                    {{-- <td class="text-center">
                                        {{ number_format($result->stock,0,",",".") }}
                                    </td> --}}
                                    <td class="text-right">
                                        @money($result->hpp)
                                    </td>
                                    <td class="text-right">
                                        @money($stock_akhir * $result->hpp)
                                    </td>
                                @else
                                    <td class="text-center"></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-center">
                            <strong>Total</strong>
                        </td>
                        @foreach ($covers as $cover)
                            @php
                                $summary = $products->where('brand_id', $cover->id);
                            @endphp
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">{{ number_format($summary->sum('masuk'),0,",",".") }}</td>
                            <td class="text-center">{{ number_format(abs($summary->sum('keluar')),0,",",".") }}</td>
                            <td class="text-center"></td>
                            {{-- <td class="text-center">{{ number_format($summary->sum('stock'),0,",",".") }}</td> --}}
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            {{-- <td class="text-right">@money($summary->sum('harga_stock'))</td> --}}
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
 $(function () {
    $('.datatable-stock').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
@endsection
