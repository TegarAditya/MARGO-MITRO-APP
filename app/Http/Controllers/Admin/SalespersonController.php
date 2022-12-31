<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySalespersonRequest;
use App\Http\Requests\StoreSalespersonRequest;
use App\Http\Requests\UpdateSalespersonRequest;
use App\Models\City;
use App\Models\Salesperson;
use App\Models\KotaSale;
use App\Models\AlamatSale;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Excel;
use App\Imports\SalespersonImport;
use Alert;
use DB;

class SalespersonController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('salesperson_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Salesperson::with(['area_pemasarans'])->select(sprintf('%s.*', (new Salesperson())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'salesperson_show';
                $editGate = 'salesperson_edit';
                $deleteGate = 'salesperson_delete';
                $crudRoutePart = 'salespeople';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('area_pemasaran', function ($row) {
                $labels = [];
                foreach ($row->area_pemasarans as $area_pemasaran) {
                    if ($area_pemasaran === $row->area_pemasarans->last()) {
                        $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $area_pemasaran->name);
                    } else {
                        $labels[] = sprintf('<span class="label label-info label-many">%s,</span>', $area_pemasaran->name);
                    }
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'area_pemasaran']);

            return $table->make(true);
        }

        return view('admin.salespeople.index');
    }

    public function create()
    {
        abort_if(Gate::denies('salesperson_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cities = City::all();

        return view('admin.salespeople.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'alamat' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $salesperson = Salesperson::create([
                'name' => $request->name,
                'telephone' => $request->telephone,
                'company' => $request->company,
            ]);

            City::whereIn('id', array_keys($request->alamat))->get()->each(function($item) use ($salesperson, $request) {
                $city = $item->id;
                $alamats = $request->alamat[$item->id]['alamat'];

                $kota_sale = KotaSale::create([
                    'sales_id' => $salesperson->id,
                    'kota_id' => $city,
                    'name' => $salesperson->name .' - '. $item->name
                ]);

                foreach($alamats as $alamat) {
                    AlamatSale::create([
                        'kota_sales_id' => $kota_sale->id,
                        'alamat' => $alamat,
                    ]);
                }
            });

            DB::commit();

            Alert::success('Success', 'Sales berhasil di simpan');

            return redirect()->route('admin.salespeople.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salesperson->load('kota');
        $cities = City::all();

        return view('admin.salespeople.edit', compact('cities', 'salesperson'));
    }

    public function update(Request $request, Salesperson $salesperson)
    {
        $request->validate([
            'name' => 'required|string',
            'alamat' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $salesperson->forceFill([
                'name' => $request->name,
                'telephone' => $request->telephone,
                'company' => $request->company,
            ])->save();

            $kota = City::whereIn('id', array_keys($request->alamat))->get()->map(function($item) use ($salesperson, $request) {
                $city = $item->id;
                $old = $request->alamat[$item->id]['id'] ?? null;
                $alamats = $request->alamat[$item->id]['alamat'];

                $kota_sale = KotaSale::firstOrCreate(
                    ['sales_id' => $salesperson->id, 'kota_id' => $city],
                    ['name' => $salesperson->name .' - '. $item->name]
                );

                foreach($alamats as $key => $item) {
                    if (isset($old[$key])) {
                        AlamatSale::updateOrCreate(
                            ['id' => $old[$key], 'kota_sales_id' => $kota_sale->id],
                            ['alamat' => $item]
                        );
                    } else {
                        AlamatSale::create(['kota_sales_id' => $kota_sale->id, 'alamat' => $item]);
                    }
                }

                return [
                    'kota_id' => $city,
                ];
            });

            $kota_deleted = KotaSale::where('sales_id', $salesperson->id)
                ->whereNotIn('kota_id', $kota->pluck('kota_id'))->get();

            foreach($kota_deleted as $item) {
                $item->alamats()->delete();
                $item->delete();
            }

            DB::commit();

            Alert::success('Success', 'Sales berhasil di simpan');

            return redirect()->route('admin.salespeople.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.salespeople.index');
    }

    public function show(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salesperson->load('area_pemasarans');

        return view('admin.salespeople.show', compact('salesperson'));
    }

    public function destroy(Salesperson $salesperson)
    {
        abort_if(Gate::denies('salesperson_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $kota_deleted = KotaSale::where('sales_id', $salesperson->id)->get();

        foreach($kota_deleted as $item) {
            $item->alamats()->delete();
            $item->delete();
        }

        $salesperson->delete();

        Alert::success('Success', 'Sales Person berhasil di hapus');

        return back();
    }

    public function massDestroy(MassDestroySalespersonRequest $request)
    {
        Salesperson::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('salesperson_create') && Gate::denies('salesperson_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Salesperson();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function import(Request $request)
    {
        $file = $request->file('import_file');
        $request->validate([
            'import_file' => 'mimes:csv,txt,xls,xlsx',
        ]);

        Excel::import(new SalespersonImport(), $file);

        Alert::success('Success', 'Sales Person berhasil di import');
        return redirect()->back();
    }

    public function select(Request $request)
    {
        $sales = $request->sales;
        $kota_sales = KotaSale::where('sales_id', $sales)->pluck('name', 'id');
        return response()->json($kota_sales);
    }
}
