<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderPackage;
use App\Models\Product;
use App\Models\Salesperson;
use App\Models\KotaSale;
use App\Models\CustomPrice;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Pembayaran;
use App\Models\Semester;
use App\Models\Price;
use App\Models\PriceDetail;
use App\Models\Tagihan;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Alert;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Order::with(['salesperson', 'tagihan', 'invoices', 'pembayarans'])->select(sprintf('%s.*', (new Order())->table));

            if (!empty($request->date)) {
                $dates = explode(' - ', $request->date);

                $start = Date::parse($dates[0])->startOfDay();
                $end = !isset($dates[1]) ? $start->clone()->endOfMonth() : Date::parse($dates[1])->endOfDay();

                $query->whereBetween('date', [$start, $end]);
            }

            if (!empty($request->sales)) {
                $query->where('salesperson_id', $request->sales);
            }

            if (!empty($request->semester)) {
                $query->where('semester_id', $request->semester);
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'order_show';
                $editGate = 'order_edit';
                $deleteGate = 'order_delete';
                $crudRoutePart = 'orders';

                return view('partials.datatablesActionsOrderIndex', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('no_order', function ($row) {
                return $row->no_order ? $row->no_order : '';
            });

            $table->addColumn('lunas', function ($row) {
                return '<span class="badge badge-'. ($row->lunas ? 'success' : 'danger') .'">'. ($row->lunas ? 'Lunas' : 'Belum Lunas'). '</span>
                    <br><span class="badge badge-'. ($row->selesai ? 'success' : 'danger') .'">'. ($row->selesai ? 'Selesai' : 'Belum Selesai'). '</span>';
            });

            $table->addColumn('salesperson_name', function ($row) {
                return $row->salesperson ? $row->salesperson->name : '';
            });

            $table->addColumn('semester_name', function ($row) {
                return $row->semester ? $row->semester->name : '';
            });

            $table->addColumn('salesperson_kotasale', function ($row) {
                return $row->kotasale ? $row->kotasale->city->name : '';
            });

            $table->addColumn('tagihan', function ($row) {
                return 'Total Order: Rp '. number_format($row->tagihan->total, 0, ',', '.') .
                '<br>Total Kirim: Rp '.number_format($row->invoices->sum('nominal'), 0, ',', '.') .
                '<br>Total Bayar: Rp '.number_format($row->pembayarans->sum('nominal'), 0, ',', '.');
            });

            $table->rawColumns(['actions', 'placeholder', 'salesperson_name', 'salesperson_kotasale', 'lunas', 'tagihan']);

            return $table->make(true);
        }

        $salespersons = Salesperson::get()->pluck('nama_sales', 'id')->prepend('Semua Sales Person', '');
        $semesters = Semester::pluck('name', 'id')->prepend('Semua Semester', '');

        return view('admin.orders.index', compact('salespersons', 'semesters'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salespeople = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $covers = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $customprices = CustomPrice::get()->pluck('nama', 'id')->prepend('Harga Normal', '');
        // $customprices = collect(['Harga Normal', '']);
        $customprices = Price::get()->pluck('nama', 'id')->prepend('Harga Normal', '');
        $isi = Category::where('type', 'isi')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjang = Category::where('type', 'jenjang')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $kelas = Category::where('type', 'kelas')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $halaman = Category::where('type', 'halaman')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $semesters = Semester::where('status', 1)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $products = collect([]);
        $kotasales = collect([])->prepend('Pilih Kota', '');

        return view('admin.orders.create', compact('salespeople', 'products', 'customprices', 'covers', 'isi', 'jenjang', 'semesters', 'kelas', 'halaman', 'kotasales'));
    }

    public function store(StoreOrderRequest $request)
    {
        $request->validate([
            'date' => 'required|date',
            'salesperson_id' => 'required|exists:salespeople,id',
            'kota_sales_id' => 'required|exists:kota_sales,id',
            'semester_id' => 'required|exists:semesters,id'
            // 'products' => 'required|array|min:1',
        ]);

        $custom_price = $request->custom_price;
        $cover = $request->cover;
        $isi = $request->isi;
        $jenjang = $request->jenjang;
        $kelas = $request->kelas;
        $semester = $request->semester;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'no_order' => Order::generateNoOrder($request->semester_id),
                'date' => $request->date,
                'salesperson_id' => $request->salesperson_id,
                'kota_sales_id' => $request->kota_sales_id,
                'semester_id' => $request->semester_id
            ]);

            $order_details = collect();

            if ($request->products) {
                Product::whereIn('id', array_keys($request->products))->get()->each(function($item) use ($order, $request, $order_details) {
                    $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                    $price = (float) $request->products[$item->id]['price'] ?: 0;
                    $unit_price = $item->price;
                    $total = $qty * $price;
                    $pg_tipe = $request->products[$item->id]['pg'] ?? null ;
                    $pg_bonus = $request->products[$item->id]['bonus'] ?? null;

                    $order_detail = OrderDetail::create([
                        'product_id' => $item->id,
                        'order_id' => $order->id,
                        'quantity' => $qty,
                        'unit_price' => $unit_price,
                        'price' => $price,
                        'total' => $total,
                    ]);

                    $order_details->push([
                        'total' => $total,
                    ]);

                    if ($item->tipe_pg === 'non_pg') {
                        if ($pg_tipe === 'pg') {
                            if ($item->pg_id) {
                                $bonus = OrderPackage::create([
                                    'product_id' => $item->pg_id,
                                    'order_id' => $order->id,
                                    'order_detail_id' => $order_detail->id,
                                    'quantity' => $pg_bonus,
                                ]);
                            }
                        } else if ($pg_tipe === 'kunci') {
                            if ($item->kunci_id) {
                                $bonus = OrderPackage::create([
                                    'product_id' =>  $item->kunci_id,
                                    'order_id' => $order->id,
                                    'order_detail_id' => $order_detail->id,
                                    'quantity' => $pg_bonus,
                                ]);
                            }
                        }
                    }
                });
            }

            $order->tagihan()->create([
                'order_id' => $order->id,
                'salesperson_id' => $order->salesperson_id,
                'total' => $order_details->sum('total'),
                'tagihan' => 0,
                'saldo' => 0,
                'retur' => 0,
                'diskon' => 0
            ]);

            DB::commit();

            // Alert::success('Success', 'Sales Order berhasil di simpan');
            return redirect()->route('admin.orders.edit', ['order' => $order->id, 'custom_price' => $custom_price, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'kelas' => $kelas, 'semester' => $semester]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(Order $order, Request $request)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salespeople = Salesperson::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $covers = Brand::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $customprices = CustomPrice::where('sales_id', $order->salesperson_id)->get()->pluck('nama', 'id')->prepend('Harga Normal', '');
        $customprices = Price::get()->pluck('nama', 'id')->prepend('Harga Normal', '');
        $isi = Category::where('type', 'isi')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjang = Category::where('type', 'jenjang')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $kelas = Category::where('type', 'kelas')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $halaman = Category::where('type', 'halaman')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $semesters = Semester::where('status', 1)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $kotasales = KotaSale::where('sales_id', $order->salesperson_id)->pluck('name', 'id');

        if ($request->cover || $request->isi || $request->jenjang || $request->custom_price || $request->kelas || $request->semester) {
            $query = Product::with(['media', 'category', 'brand', 'isi', 'jenjang', 'semester', 'pg', 'pg.category', 'pg.brand', 'pg.isi', 'pg.jenjang', 'pg.semester']);
            if ($request->cover) {
                $query->where('brand_id', $request->cover);
            }
            if ($request->isi) {
                $query->where('isi_id', $request->isi);
            }
            if ($request->jenjang) {
                $query->where('jenjang_id', $request->jenjang);
            }
            if ($request->kelas) {
                $query->where('kelas_id', $request->kelas);
            }
            if ($request->semester) {
                $query->where('semester_id', $request->semester);
            }
            $custom_price = null;
            if ($request->custom_price) {
                $custom = Price::find($request->custom_price);
                $detail = PriceDetail::where('price_id', $custom->id)->where('sales_id', $order->salesperson_id)->first();
                if ($detail) {
                    $custom_price = $detail->custom_price;
                } else {
                    $custom_price = $custom->price;
                }
                $kategori = $custom->category_id;

                $query->where('halaman_id', $kategori);
            }

            $products = $query->get();

            foreach($products as $product) {
                if ($product->pg && $product->pg->brand_id !== $request->cover) {
                    $products->push($product->pg);
                }
            }

            if ($custom_price) {
                $products->map(function($product) use($custom_price) {
                    if ($product->tipe_pg === 'non_pg') {
                        $product->price = $custom_price;
                    } else if ($product->tipe_pg === 'pg') {
                        $product->price = 6000;
                    }
                    return $product;
                });
            }
        } else {
            $products = collect([]);
        }

        $order->load([
            'salesperson',
            'order_details',
            'order_details.product',
            'order_details.bonus',
            'tagihan',
            'pembayarans',
            'invoices',
            'semester'
        ]);

        return view('admin.orders.edit', compact('order', 'salespeople', 'products', 'customprices', 'covers', 'isi', 'jenjang', 'semesters', 'kelas', 'halaman', 'kotasales'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $request->validate([
            'date' => 'required|date',
            'salesperson_id' => 'required|exists:salespeople,id',
            'kota_sales_id' => 'required|exists:kota_sales,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        $custom_price = $request->custom_price;
        $cover = $request->cover;
        $isi = $request->isi;
        $jenjang = $request->jenjang;
        $kelas = $request->kelas;
        $semester = $request->semester;

        if ($request->filter) {
            $order->forceFill([
                'date' => $request->date,
                'salesperson_id' => $request->salesperson_id,
                'kota_sales_id' => $request->kota_sales_id,
                'semester_id' => $request->semester_id
            ])->save();

            return redirect()->route('admin.orders.edit', ['order' => $order->id, 'custom_price' => $custom_price, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'kelas' => $kelas, 'semester' => $semester]);
        }

        $request->validate([
            'products' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $order->forceFill([
                'date' => $request->date,
                'salesperson_id' => $request->salesperson_id,
                'kota_sales_id' => $request->kota_sales_id,
                'semester_id' => $request->semester_id
            ])->save();

            $detail_collection = collect();

            // Update with new items
            Product::whereIn('id', array_keys($request->products))->get()->each(function($item) use ($order, $request, $detail_collection) {
                $qty = (int) $request->products[$item->id]['qty'] ?: 0;
                $price = (float) $request->products[$item->id]['price'] ?: 0;
                $unit_price = $item->price;
                $total = $qty * $price;
                $pg_tipe = $request->products[$item->id]['pg'] ?? null ;
                $pg_bonus = $request->products[$item->id]['bonus'] ?? null;

                $order_detail = $order->order_details->where('product_id', $item->id)->first() ?: new OrderDetail;

                $order_detail->forceFill([
                    'product_id' => $item->id,
                    'order_id' => $order->id,
                    'quantity' => $qty,
                    'unit_price' => $unit_price,
                    'price' => $price,
                    'total' => $total,
                ])->save();

                if ($item->tipe_pg === 'non_pg') {
                    if ($pg_tipe === 'pg') {
                        if ($item->pg_id) {
                            if ($order_detail->bonus) {
                                $bonus = $order_detail->bonus->forceFill([
                                    'product_id' => $item->pg_id,
                                    'quantity' => $pg_bonus,
                                ])->save();
                            } else {
                                $bonus = OrderPackage::create([
                                    'product_id' => $item->pg_id,
                                    'order_id' => $order->id,
                                    'order_detail_id' => $order_detail->id,
                                    'quantity' => $pg_bonus,
                                ]);
                            }
                        }
                    } else if ($pg_tipe === 'kunci') {
                        if ($item->kunci_id) {
                            if ($order_detail->bonus) {
                                $bonus = $order_detail->bonus->forceFill([
                                    'product_id' => $item->kunci_id,
                                    'quantity' => $pg_bonus,
                                ])->save();
                            } else {
                                $bonus = OrderPackage::create([
                                    'product_id' => $item->kunci_id,
                                    'order_id' => $order->id,
                                    'order_detail_id' => $order_detail->id,
                                    'quantity' => $pg_bonus,
                                ]);
                            }
                        }
                    }
                }

                $detail_collection->push([
                    'product_id' => $item->id,
                    'order_id' => $order->id,
                    'quantity' => $qty,
                    'unit_price' => $unit_price,
                    'price' => $price,
                    'total' => $total,
                    'order_detail_id' => $order_detail->id
                ]);
            });

            $order->order_details()
                ->whereNotIn('product_id', $detail_collection->pluck('product_id'))
                ->forceDelete();

            OrderPackage::where('order_id', $order->id)
                ->whereNotIn('order_detail_id', $detail_collection->pluck('order_detail_id'))
                ->delete();

            // Last but not least
            $tagihan = $order->tagihan()->firstOrNew([
                'order_id' => $order->id,
            ]);

            $tagihan->salesperson_id = $order->salesperson_id;
            $tagihan->semester_id = $order->semester_id;
            $tagihan->total = $detail_collection->sum('total');
            $tagihan->tagihan = $order->invoices()->sum('nominal') ?: 0;
            $tagihan->saldo = $tagihan->tagihan_movements()->sum('nominal') ?: 0;
            $tagihan->save();

            DB::commit();

            Alert::success('Success', 'Sales Order berhasil di simpan');

            return redirect()->route('admin.orders.edit', ['order' => $order->id, 'custom_price' => $custom_price, 'cover' => $cover, 'isi' => $isi, 'jenjang' => $jenjang, 'kelas' => $kelas, 'semester' => $semester]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('salesperson', 'semester');

        return view('admin.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('invoices');

        if ($order->invoices->count() > 0) {
            Alert::warning('Warning', 'Sales Order tidak bisa di hapus');
            return back();
        }

        OrderDetail::where('order_id', $order->id)->forceDelete();
        $order->delete();
        Alert::success('Success', 'Sales Order berhasil dihapus');

        return back();
    }

    public function massDestroy(MassDestroyOrderRequest $request)
    {
        Order::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function print_estimasi(Request $request)
    {
        $order = Order::findOrFail($request->id);

        $details = OrderDetail::where('order_id', $order->id)
                ->with(['product', 'bonus', 'product.jenjang', 'product.jadi_pg'])
                ->get()
                ->sortBy('product.tipe_pg')
                ->sortBy('product.halaman_id')
                ->sortBy('product.kelas_id')
                ->sortBy('product.tiga_nama')
                ->sortBy('product.jenjang_id');

        $collections = collect([]);

        $products = $details->where('product.tipe_pg', '=', 'non_pg');
        foreach($products as $detail) {
            $product = $detail->product;
            $bonus = $detail->bonus;
            $sisa = $detail->quantity - $detail->moved;
            $pg = $bonus ? $bonus->quantity - $bonus->moved : 0;

            $collections->push([
                'product_id' => $product->id,
                'cover' => $product->brand->name ?? '',
                'mapel' => $product->name,
                'kelas' => $product->kelas->name ?? '',
                'hal' => $product->halaman->name ?? '',
                'jenjang' => $product->jenjang->name ?? '',
                'sisa' => $sisa,
                'kelengkapan' => $pg,
            ]);
        }

        $bonus = $details->where('product.tipe_pg', '!=', 'non_pg');
        foreach($bonus as $detail) {
            $product = $detail->product->jadi_pg;
            $sisa = 0;
            $pg = $detail->quantity - $detail->moved;
            $diganti = $product->id;
            $result = $collections->search(function($item) use($diganti)  {
                return $item['product_id'] === $diganti;
            });
            if ($result !== false) {
                $collections->transform(function($item) use ($pg, $diganti) {
                    if ($item['product_id'] === $diganti) {
                        $kelengkapan = $item['kelengkapan'] + $pg;
                    } else {
                        $kelengkapan = $item['kelengkapan'];
                    }

                    return [
                        'product_id' => $item['product_id'],
                        'cover' => $item['cover'],
                        'mapel' => $item['mapel'],
                        'kelas' => $item['kelas'],
                        'hal' => $item['hal'],
                        'jenjang' => $item['jenjang'],
                        'sisa' => $item['sisa'],
                        'kelengkapan' => $kelengkapan,
                    ];
                });
            } else {
                $collections->push([
                    'product_id' => $product->id,
                    'cover' => $product->brand->name ?? '',
                    'mapel' => $product->name,
                    'kelas' => $product->kelas->name ?? '',
                    'hal' => $product->halaman->name ?? '',
                    'jenjang' => $product->jenjang->name ?? '',
                    'sisa' => $sisa,
                    'kelengkapan' => $pg,
                ]);
            }
        }

        $groups = $collections->groupBy('jenjang');

        return view('admin.orders.prints.estimasi', compact('order', 'details', 'groups'));
    }

    public function print_saldo(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->load('salesperson');

        $total_invoice = Invoice::where('order_id', $order->id)->sum('nominal');
        $kirims = Invoice::with('invoice_details')->where('order_id', $order->id)->where('nominal', '>=', 0)->get();
        $returs = Invoice::with('invoice_details')->where('order_id', $order->id)->where('nominal', '<', 0)->get();
        $pembayarans = Pembayaran::where('order_id', $order->id)->get();

        return view('admin.orders.prints.saldo', compact('order', 'total_invoice', 'kirims', 'returs', 'pembayarans'));
    }

    public function print_saldo_rekap(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->load('salesperson');

        $total_invoice = Invoice::where('order_id', $order->id)->sum('nominal');
        $kirims = Invoice::with('invoice_details')->where('order_id', $order->id)->where('nominal', '>=', 0)->get();
        $returs = Invoice::with('invoice_details')->where('order_id', $order->id)->where('nominal', '<', 0)->get();
        $pembayarans = Pembayaran::where('order_id', $order->id)->get();

        return view('admin.orders.prints.saldo_rekap', compact('order', 'total_invoice', 'kirims', 'returs', 'pembayarans'));
    }

    public function change_price(Request $request)
    {
        $order_id = $request->order_id;
        $harga_awal = $request->harga_awal;
        $harga_koreksi = $request->harga_koreksi;
        $halaman = $request->hal_harga;

        DB::beginTransaction();
        try {
            $order = Order::with(['order_details' => function($q) use($harga_awal) {
                $q->where('price', $harga_awal);
            }, 'invoices.invoice_details' => function($q) use($harga_awal) {
                $q->where('price', $harga_awal);
            }])->where('id', $order_id)->first();

            foreach($order->order_details as $order_detail) {
                if ($order_detail->product->halaman_id == $halaman) {
                    $qty = $order_detail->quantity;

                    $order_detail->update([
                        'price' => $harga_koreksi,
                        'total' => $qty * $harga_koreksi,
                    ]);
                }
            }

            foreach($order->invoices as $invoice) {
                foreach($invoice->invoice_details as $invoice_detail) {
                    if ($invoice_detail->product->halaman_id == $halaman) {
                        $qty = $invoice_detail->quantity;

                        $invoice_detail->update([
                            'price' => $harga_koreksi,
                            'total' => $qty * $harga_koreksi,
                        ]);
                    }
                }

                if ($invoice->invoice_details) {
                    $inv_edit = Invoice::with('invoice_details')->where('id', $invoice->id)->first();
                    $inv_edit->update([
                        'nominal' => $inv_edit->invoice_details->sum('total')
                    ]);
                }
            }

            Tagihan::where('order_id', $order_id)->update([
                'total' => OrderDetail::where('order_id', $order_id)->sum('total'),
                'tagihan' => Invoice::where('order_id', $order_id) ->sum('nominal')
            ]);

            DB::commit();

            Alert::success('Success', 'Harga berhasil diubah');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();;
        }
    }

    public function change_price_single(Request $request)
    {
        dd('aaa');
    }
}
