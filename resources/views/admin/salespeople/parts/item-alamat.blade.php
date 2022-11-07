@php
$kota = $detail->kota ?: new App\Models\City;
$alamat = $detail->alamats ?: null
@endphp
<div class="row item-alamat" data-id="{{ $detail->id }}">
    <div class="col-10 alamat-container">
        <h5 class="mb-1">KOTA <span class="detail-kota">{{ $kota->name }}</span></h5>
        @if ($alamat)
            @foreach ($alamat as $item)
                <input type="hidden" name="alamat[{{ $detail->kota_id ?: 0 }}][id][]" value="{{ $item->id }}">
                <textarea
                    class="form-control detail-alamat mt-1"
                    name="alamat[{{ $detail->kota_id ?: 0 }}][alamat][]"
                    placeholder="Masukkan Alamat..."
                    style="min-height: 50px;">{{ $item->alamat }}</textarea>
            @endforeach
        @else
            <textarea
                class="form-control detail-alamat mt-1"
                name="alamat[{{ $detail->kota_id ?: 0 }}][alamat][]"
                placeholder="Masukkan Alamat..."
                style="min-height: 50px;"></textarea>
        @endif
    </div>

    <div class="col-2">
        {{-- @if (!$detail->id) --}}
        <div class="text-center" style="margin-top: 40px;">
            <button class="btn btn-success btn-sm detail-add">
                <i class="fa fa-plus"></i>
            </button>
            <button class="btn btn-danger btn-sm detail-delete">
                <i class="fa fa-trash"></i>
            </button>
        </div>

        {{-- @endif --}}
    </div>
</div>
