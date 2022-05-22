<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPembayaranRequest;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Requests\UpdatePembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\TagihanMovement;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PembayaranController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('pembayaran_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembayarans = Pembayaran::with(['tagihan'])->get();

        return view('admin.pembayarans.index', compact('pembayarans'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('pembayaran_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembayaran = new Pembayaran();
        $tagihan = !$request->tagihan_id ? new Tagihan : Tagihan::find($request->tagihan_id);
        $pembayarans = $tagihan->pembayarans;

        $tagihans = Tagihan::with('order')->get();

        return view('admin.pembayarans.create', compact('tagihans', 'pembayaran', 'pembayarans', 'tagihan'));
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
            ]);

            DB::commit();

            if ($request->redirect) {
                return redirect($request->redirect);
            }

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

        $tagihans = Tagihan::with('order')->get();

        return view('admin.pembayarans.edit', compact('tagihans', 'pembayaran', 'pembayarans', 'tagihan'));
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
            ]);

            DB::commit();

            if ($request->redirect) {
                return redirect($request->redirect);
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

        $pembayaran->load(['tagihan', 'tagihan.pembayarans']);

        if (request('print')) {
            return view('admin.pembayarans.prints.kwitansi', compact('pembayaran'));
        }

        return view('admin.pembayarans.show', compact('pembayaran'));
    }

    public function destroy(Pembayaran $pembayaran)
    {
        abort_if(Gate::denies('pembayaran_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembayaran->delete();

        return back();
    }

    public function massDestroy(MassDestroyPembayaranRequest $request)
    {
        Pembayaran::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
