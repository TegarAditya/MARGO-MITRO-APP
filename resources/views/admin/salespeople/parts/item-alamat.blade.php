@php
$kota = $detail->kota ?: new App\Models\City;
@endphp
<div class="row item-alamat" data-id="{{ $detail->id }}">
    <div class="col-10">
        <h5 class="mb-1">KOTA <span class="detail-kota">{{ $kota->name }}</span></h5>
        <textarea
            class="form-control detail-alamat mt-1"
            name="alamat[{{ $detail->kota_id ?: 0 }}][alamat]"
            placeholder="Masukkan Alamat..."
            style="min-height: 50px;">{{ $detail->alamat }}</textarea>
    </div>

    <div class="col-2">
        {{-- @if (!$detail->id) --}}
        <div class="text-center" style="margin-top: 40px;">
            <button class="btn btn-danger btn-sm detail-delete">
                <i class="fa fa-trash"></i>
            </button>
        </div>

        {{-- @endif --}}
    </div>
</div>
