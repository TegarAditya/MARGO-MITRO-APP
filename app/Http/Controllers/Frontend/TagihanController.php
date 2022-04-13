<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTagihanRequest;
use App\Http\Requests\StoreTagihanRequest;
use App\Http\Requests\UpdateTagihanRequest;
use App\Models\Order;
use App\Models\Salesperson;
use App\Models\Tagihan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagihanController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tagihan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihans = Tagihan::with(['order', 'salesperson'])->get();

        return view('frontend.tagihans.index', compact('tagihans'));
    }

    public function create()
    {
        abort_if(Gate::denies('tagihan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('no_order', 'id')->prepend(trans('global.pleaseSelect'), '');

        $salespeople = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.tagihans.create', compact('orders', 'salespeople'));
    }

    public function store(StoreTagihanRequest $request)
    {
        $tagihan = Tagihan::create($request->all());

        return redirect()->route('frontend.tagihans.index');
    }

    public function edit(Tagihan $tagihan)
    {
        abort_if(Gate::denies('tagihan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('no_order', 'id')->prepend(trans('global.pleaseSelect'), '');

        $salespeople = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tagihan->load('order', 'salesperson');

        return view('frontend.tagihans.edit', compact('orders', 'salespeople', 'tagihan'));
    }

    public function update(UpdateTagihanRequest $request, Tagihan $tagihan)
    {
        $tagihan->update($request->all());

        return redirect()->route('frontend.tagihans.index');
    }

    public function show(Tagihan $tagihan)
    {
        abort_if(Gate::denies('tagihan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihan->load('order', 'salesperson');

        return view('frontend.tagihans.show', compact('tagihan'));
    }

    public function destroy(Tagihan $tagihan)
    {
        abort_if(Gate::denies('tagihan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tagihan->delete();

        return back();
    }

    public function massDestroy(MassDestroyTagihanRequest $request)
    {
        Tagihan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
