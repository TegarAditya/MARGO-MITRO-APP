@php
$pagination = !isset($pagination) ? new Illuminate\Pagination\LengthAwarePaginator() : $pagination;
$selected_ids = !isset($selected_ids) ? [] : $selected_ids;
@endphp

@foreach ($pagination->items() as $product)
@php
$category = $product->category;
$selected = in_array($product->id, $selected_ids);
@endphp
<a
    href="{{ route('admin.products.show', $product->id) }}"
    class="product-select-item{{ $selected ? ' selected' : '' }}"
    data-id="{{ $product->id }}"
    data-price="{{ $product->price }}"
    data-hpp="{{ $product->hpp }}"
    data-stock="{{ $product->stock }}"
    @if ($foto = $product->foto->first())
        data-image="{{ $foto->getUrl('thumb') }}"
    @endif
>
    <div class="row">
        @if ($product->foto && $foto = $product->foto->first())
            <div class="col-auto pr-1">
                <img src="{{ $foto->getUrl('thumb') }}" class="product-img" />
            </div>
        @endif
    
        <div class="col">
            <div class="product-content">
                <h6 class="text-sm product-name mb-1">{{ $product->name }}</h6>

                <p class="mb-0 text-sm">
                    HPP: <span class="product-hpp">@money($product->hpp)</span>
                </p>

                <p class="mb-0 text-sm">
                    Category: <span class="product-category">{{ !$category ? '' : $category->name }}</span>
                </p>

                <p class="mb-0 text-sm">
                    Stock: <span class="product-stock">{{ $product->stock }}</span>
                </p>
            </div>
        </div>
    </div>
</a>
@endforeach
