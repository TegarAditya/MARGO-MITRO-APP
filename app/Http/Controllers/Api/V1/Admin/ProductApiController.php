<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ProductApiController extends Controller
{
    use MediaUploadingTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductResource(Product::with(['category', 'brand', 'unit'])->get());
    }

    public function paginate(Request $request)
    {
        $query = Product::query()->with(['category']);
        $appends = [];

        if ($request->get('category_ids')) {
            $category_ids = explode(',', $request->category_ids);

            $query->whereIn('category_id', $category_ids);
            $appends['category_id'] = $category_ids;
        }

        if ($request->search) {
            $query->where(function($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('description', 'LIKE', "%{$request->search}%");
            });
        }

        $pagination = $query->paginate(25)->onEachSide(2);

        return response()->json([
            'pagination' => $pagination,
            'html' => !View::exists($request->component) ? '' : view($request->component, [
                'pagination' => $pagination,
                'selected_ids' => !$request->selected_ids ? [] : explode(',', $request->selected_ids),
            ])->render(),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());

        foreach ($request->input('foto', []) as $file) {
            $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto');
        }

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductResource($product->load(['category', 'brand', 'unit']));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        if (count($product->foto) > 0) {
            foreach ($product->foto as $media) {
                if (!in_array($media->file_name, $request->input('foto', []))) {
                    $media->delete();
                }
            }
        }
        $media = $product->foto->pluck('file_name')->toArray();
        foreach ($request->input('foto', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto');
            }
        }

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
