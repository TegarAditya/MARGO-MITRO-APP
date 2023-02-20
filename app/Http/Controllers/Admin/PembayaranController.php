<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPembayaranRequest;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Requests\StorePembayaranGeneralRequest;
use App\Http\Requests\UpdatePembayaranRequest;
use App\Models\Order;
use App\Models\Pembayaran;
use App\Models\Salesperson;
use App\Models\Tagihan;
use App\Models\TagihanMovement;
use App\Models\Saldo;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Date;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use App\Exports\Admin\RekapSaldoExport;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('pembayaran_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Pembayaran::with(['tagihan'])->select(sprintf('%s.*', (new Pembayaran())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'pembayaran_show';
                $editGate = 'pembayaran_edit';
                $deleteGate = 'pembayaran_delete';
                $crudRoutePart = 'pembayarans';
                $parent = 'orders';
                $idParent = $row->order->id;

                return view('partials.datatableOrderActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'parent',
                    'idParent',
                    'row'
                ));
            });

            $table->editColumn('no_kwitansi', function ($row) {
                return $row->no_kwitansi ? $row->no_kwitansi : '';
            });
            $table->addColumn('tagihan_saldo', function ($row) {
                return $row->tagihan ? $row->tagihan->saldo : '';
            });

            $table->editColumn('nominal', function ($row) {
                return $row->nominal ? $row->nominal : '';
            });
            $table->editColumn('diskon', function ($row) {
                return $row->diskon ? $row->diskon : '';
            });
            $table->editColumn('bayar', function ($row) {
                return $row->bayar ? $row->bayar : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'tagihan']);

            return $table->make(true);
        }

        $saldos = Salesperson::with(['orders.invoices' => function($query) {
            $query->select(DB::raw('SUM(nominal)'));
        }])->withCount(['tagihans as pesanan' => function($query) {
            $query->select(DB::raw('SUM(total)'));
        }, 'tagihans as tagihan' => function($query) {
            $query->select(DB::raw('SUM(tagihan)'));
        }, 'tagihans as bayar' => function($query) {
            $query->select(DB::raw('SUM(saldo)'));
        }, 'tagihans as retur' => function($query) {
            $query->select(DB::raw('SUM(retur)'));
        }, 'tagihans as diskon' => function($query) {
            $query->select(DB::raw('SUM(diskon)'));
        }])->whereHas('orders')->orderBy('id', 'ASC')->get();

        $periode = Saldo::groupBy('periode')->pluck('periode', 'kode');

        return view('admin.pembayarans.index', compact('saldos', 'periode'));
    }

    public function periode(Request $request)
    {
        $saldos = Saldo::where('kode', $request->periode)->get();
        $title = $saldos->first()->periode;

        return view('admin.pembayarans.saldo', compact('saldos', 'title'));
    }

    public function jangka(Request $request)
    {
        if ($request->has('date') && $request->date && $dates = explode(' - ', $request->date)) {
            $start = Date::parse($dates[0])->startOfDay();
            $end = !isset($dates[1]) ? $start->clone()->endOfMonth() : Date::parse($dates[1])->endOfDay();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
        }

        $saldos = Salesperson::with(['invoices' => function($query) use($start, $end) {
            $query->whereBetween('invoices.date', [$start, $end]);
        }, 'pembayarans' => function($query) use($start, $end) {
            $query->whereBetween('pembayarans.tanggal', [$start, $end]);
        }])->whereHas('orders')->orderBy('id', 'ASC')->get();

        return view('admin.pembayarans.periode', compact('saldos'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('pembayaran_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembayaran = new Pembayaran();
        $tagihan = !$request->tagihan_id ? new Tagihan : Tagihan::find($request->tagihan_id);
        $pembayarans = $tagihan->pembayarans;
        $order = $tagihan->order ?: new Order();

        if ($order) {
            $order->load('invoices', 'pembayarans');
        }

        $tagihans = Tagihan::with('order', 'order.invoices', 'order.pembayarans')->get();

        return view('admin.pembayarans.create', compact('tagihans', 'pembayaran', 'pembayarans', 'tagihan', 'order'));
    }

    public function store(StorePembayaranRequest $request)
    {
        $tagihan = Tagihan::findOrFail($request->tagihan_id);

        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::create($request->merge([
                'no_kwitansi' => Pembayaran::generateNoKwitansi(),
                'order_id' => $tagihan->order_id,
            ])->all());

            $tagihan->tagihan_movements()->create([
                'tagihan_id' => $tagihan->id,
                'reference' => $pembayaran->id,
                'type' => 'pembayaran',
                'nominal' => (float) $request->bayar,
            ]);
            $tagihan->update([
                'saldo' => $tagihan->saldo + (float) $request->nominal,
                'diskon' => $tagihan->diskon + (float) $request->diskon,
            ]);

            DB::commit();

            if ($request->redirect) {
                return redirect($request->redirect)->with('activeTabs', 'invoice');
            }

            Alert::success('Success', 'Pembayaran berhasil di simpan');

            return redirect()->route('admin.pembayarans.edit', $pembayaran->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(Request $request, Pembayaran $pembayaran)
    {
        abort_if(Gate::denies('pembayaran_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembayaran->load(['tagihan', 'tagihan.pembayarans']);

        $tagihan = $pembayaran->tagihan ?: new Tagihan;
        $pembayarans = $tagihan->pembayarans;
        $order = $tagihan->order;

        if ($order) {
            $order->load('invoices', 'pembayarans');
        }

        $tagihans = Tagihan::with('order')->get();

        return view('admin.pembayarans.edit', compact('tagihans', 'pembayaran', 'pembayarans', 'tagihan', 'order'));
    }

    public function update(UpdatePembayaranRequest $request, Pembayaran $pembayaran)
    {
        $tagihan = Tagihan::findOrFail($request->tagihan_id);

        DB::beginTransaction();
        try {
            $pembayaran->forceFill([
                'order_id' => $tagihan->order_id,
                'tagihan_id' => $tagihan->id,
                'nominal' => $request->nominal,
                'diskon' => $request->diskon ?: null,
                'bayar' => $request->bayar,
                'tanggal' => $request->tanggal,
            ])->save();

            $tagihan->tagihan_movements()->updateOrCreate([
                'tagihan_id' => $tagihan->id,
                'reference' => $pembayaran->id,
                'type' => 'pembayaran',
            ], [
                'tagihan_id' => $tagihan->id,
                'reference' => $pembayaran->id,
                'type' => 'pembayaran',
                'nominal' => (float) $request->bayar,
            ]);
            $tagihan->update([
                'saldo' => $tagihan->tagihan_movements()->sum('nominal') ?: 0,
                'diskon' => $tagihan->pembayarans()->sum('diskon') ?: 0,
            ]);

            DB::commit();

            Alert::success('Success', 'Pembayaran berhasil di simpan');

            if ($request->redirect) {
                return redirect($request->redirect)->with('activeTabs', 'invoice');
            }

            return redirect()->route('admin.pembayarans.edit', $pembayaran->id);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function show(Pembayaran $pembayaran)
    {
        abort_if(Gate::denies('pembayaran_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembayaran->load(['tagihan', 'tagihan.pembayarans', 'order', 'order.pembayarans', 'order.invoices']);

        if (request('print')) {
            return view('admin.pembayarans.prints.kwitansi', compact('pembayaran'));
        }

        return view('admin.pembayarans.show', compact('pembayaran'));
    }

    public function destroy(Pembayaran $pembayaran)
    {
        abort_if(Gate::denies('pembayaran_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihan = Tagihan::findOrFail($pembayaran->tagihan_id);

        DB::beginTransaction();
        try {
            $tagihan->tagihan_movements()
                ->where('type', 'pembayaran')
                ->where('reference', $pembayaran->id)
                ->where('tagihan_id', $tagihan->id)
                ->delete();

            $tagihan->update([
                'saldo' => $tagihan->tagihan_movements()->sum('nominal') ?: 0,
                'diskon' => $tagihan->pembayarans()->sum('diskon') ?: 0,
            ]);

            $pembayaran->delete();

            DB::commit();

            Alert::success('Success', 'Pembayaran berhasil di hapus');

            return redirect()->route('admin.pembayarans.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function massDestroy(MassDestroyPembayaranRequest $request)
    {
        // Pembayaran::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function general(Request $request)
    {
        abort_if(Gate::denies('pembayaran_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sales = Salesperson::with('tagihans')->whereHas('tagihans', function($q){
            $q->whereRaw('tagihan > saldo')
            ->whereRaw('total > tagihan ');
         })->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pembayarans.general', compact('sales'));
    }

    public function generalSave(StorePembayaranGeneralRequest $request)
    {
        $orders = Order::select('orders.*', 'tagihans.id as tagihan_id','tagihans.total', 'tagihans.tagihan', 'tagihans.saldo')
                    ->join('tagihans', 'orders.id', '=', 'tagihans.order_id')
                    ->where('orders.salesperson_id', $request->sales_id)
                    ->whereRaw('tagihans.tagihan > tagihans.saldo')
                    ->whereRaw('tagihans.total > tagihans.tagihan')
                    ->orderBy('orders.date', 'ASC')
                    ->get();

        $saldo = (float) $request->nominal;
        $disc_desc = (float) ($request->diskon / $request->bayar);

        DB::beginTransaction();
        try {
            $counter = 0;
            while($saldo > 0) {

                $sisa = $orders[$counter]->tagihan - $orders[$counter]->saldo;

                if ($sisa >= $saldo) {
                    $nominal = $saldo;
                } else {
                    $nominal = $sisa;
                }

                $saldo = $saldo - $nominal;
                $bayar = (1/(1+$disc_desc)) * $nominal;
                $diskon = $nominal - $bayar;

                $tagihan = Tagihan::findOrFail($orders[$counter]->tagihan_id);
                $pembayaran = Pembayaran::create([
                    'order_id' => $orders[$counter]->id,
                    'no_kwitansi' => Pembayaran::generateNoKwitansi(),
                    'nominal' => $nominal,
                    'diskon' => $diskon,
                    'bayar' => $bayar,
                    'tanggal' => $request->tanggal,
                    'note' => 'Batch Payment',
                    'tagihan_id' => $orders[$counter]->tagihan_id
                ]);
                $tagihan->tagihan_movements()->create([
                    'tagihan_id' => $orders[$counter]->tagihan_id,
                    'reference' => $pembayaran->id,
                    'type' => 'pembayaran',
                    'nominal' => (float) $nominal,
                ]);
                $tagihan->update([
                    'saldo' => $tagihan->saldo + (float) $nominal,
                    'diskon' => $tagihan->diskon + (float) $diskon,
                ]);

                $counter++;
            }

            DB::commit();

            Alert::success('Success', 'Pembayaran berhasil di simpan');

            return redirect()->route('admin.pembayarans.general');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function getTagihan(Request $request)
    {
        $tagihans = Tagihan::where('salesperson_id', $request->sales_id)
                ->whereRaw('tagihan > saldo')
                ->whereRaw('total > tagihan ')
                ->get();

        $tagihan = $tagihans->sum('tagihan');
        $saldo = $tagihans->sum('saldo');
        $sisa = $tagihan - $saldo;

        if ($tagihans) {
            return response()->json(['status' => 'success', 'message' => 'Data ditemukan', 'data' => ['tagihan' => $tagihan, 'saldo' => $saldo, 'sisa' => $sisa]]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Not Found']);
        }
    }

    public function rekapSaldoExport()
    {
        $saldos = Salesperson::with(['orders.invoices' => function($query) {
            $query->select(DB::raw('SUM(nominal)'));
        }])->withCount(['tagihans as pesanan' => function($query) {
            $query->select(DB::raw('SUM(total)'));
        }, 'tagihans as tagihan' => function($query) {
            $query->select(DB::raw('SUM(tagihan)'));
        }, 'tagihans as bayar' => function($query) {
            $query->select(DB::raw('SUM(saldo)'));
        }, 'tagihans as retur' => function($query) {
            $query->select(DB::raw('SUM(retur)'));
        }, 'tagihans as diskon' => function($query) {
            $query->select(DB::raw('SUM(diskon)'));
        }])->whereHas('orders')->orderBy('id', 'ASC')->get();

        return (new RekapSaldoExport($saldos))->download('Laporan Saldo.xlsx');
    }
}
