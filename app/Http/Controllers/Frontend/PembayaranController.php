<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPembayaranRequest;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Requests\UpdatePembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PembayaranController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('pembayaran_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembayarans = Pembayaran::with(['tagihan'])->get();

        return view('frontend.pembayarans.index', compact('pembayarans'));
    }

    public function create()
    {
        abort_if(Gate::denies('pembayaran_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihans = Tagihan::pluck('saldo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.pembayarans.create', compact('tagihans'));
    }

    public function store(StorePembayaranRequest $request)
    {
        $pembayaran = Pembayaran::create($request->all());

        return redirect()->route('frontend.pembayarans.index');
    }

    public function edit(Pembayaran $pembayaran)
    {
        abort_if(Gate::denies('pembayaran_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihans = Tagihan::pluck('saldo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pembayaran->load('tagihan');

        return view('frontend.pembayarans.edit', compact('pembayaran', 'tagihans'));
    }

    public function update(UpdatePembayaranRequest $request, Pembayaran $pembayaran)
    {
        $pembayaran->update($request->all());

        return redirect()->route('frontend.pembayarans.index');
    }

    public function show(Pembayaran $pembayaran)
    {
        abort_if(Gate::denies('pembayaran_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembayaran->load('tagihan');

        return view('frontend.pembayarans.show', compact('pembayaran'));
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
