@extends('layouts.admin')
@section('content')
@can('invoice_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.invoices.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.invoice.title_singular') }}
            </a>
            <a class="btn btn-success" href="{{ route('admin.invoices.retur') }}">
                {{ trans('global.add') }} Faktur Retur
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.invoice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <form id="filterform">
            <div class="row mb-5">
                <div class="col row">

                    <div class="col-4">
                        <div class="form-group mb-0">
                            <label class="small mb-0" for="salesperson_id">Sales Person</label>
                            <select class="form-control select2 {{ $errors->has('salesperson_id') ? 'is-invalid' : '' }}" name="salesperson_id" id="salesperson_id">
                                @foreach($salespersons as $id => $entry)
                                    <option value="{{ $id }}" {{ old('salesperson_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('salesperson_id'))
                                <span class="text-danger">{{ $errors->first('salesperson_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group mb-0">
                            <label class="small mb-0" for="salesperson_id">Semester</label>
                            <select class="form-control select2 {{ $errors->has('semester_id') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id">
                                @foreach($semesters as $id => $entry)
                                    <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('semester_id'))
                                <span class="text-danger">{{ $errors->first('semester_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-4">
                        <x-admin.form-group
                            type="text"
                            id="date"
                            name="date"
                            containerClass=" m-0"
                            boxClass=" px-2 py-1"
                            class="form-control-sm product-price"
                            value="{{ request('date', old('date'))}}"
                            placeholder="Pilih Tanggal"
                        >
                            <x-slot name="label">
                                <label class="small mb-0" for="date">Tanggal</label>
                            </x-slot>

                            <x-slot name="right">
                                <button type="button" class="btn btn-sm border-0 btn-default px-2 date-clear" data-action="+" style="display:{{ !request('date', old('date')) ? 'none' : 'block' }}">
                                    <i class="fa fa-times"></i>
                                </button>
                            </x-slot>
                        </x-admin.form-group>
                    </div>
                </div>

                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Invoice">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    {{-- <th>
                        {{ trans('cruds.invoice.fields.no_suratjalan') }}
                    </th> --}}
                    {{-- <th>
                        {{ trans('cruds.invoice.fields.no_invoice') }}
                    </th> --}}
                    <th>
                        No Invoice & Surat Jalan
                    </th>
                    <th>
                        {{ trans('cruds.invoice.fields.order') }}
                    </th>
                    <th>
                        {{ trans('cruds.invoice.fields.date') }}
                    </th>
                    <th>
                        {{ trans('cruds.order.fields.salesperson') }}
                    </th>
                    <th>
                        {{ trans('cruds.invoice.fields.nominal') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('invoice_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.invoices.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    // buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
        url: "{{ route('admin.invoices.index') }}",
        data: function(data) {
            data.date = $('#date').val(),
            data.sales = $('#salesperson_id').val()
            data.semester = $('#semester_id').val()
        }
    },
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        // { data: 'no_suratjalan', name: 'no_suratjalan', class: 'text-center' },
        { data: 'no_invoice', name: 'no_invoice', class: 'text-center' },
        { data: 'order', name: 'order', class: 'text-center' },
        { data: 'date', name: 'date', class: 'text-center' },
        { data: 'sales', name: 'sales', class: 'text-center' },
        { data: 'nominal', name: 'nominal', class: 'text-right', render: function(value) { return numeral(value).format('$0,0'); } },
        { data: 'actions', name: '{{ trans('global.actions') }}', class: 'text-center' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Invoice').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

    $("#filterform").submit(function(event) {
        event.preventDefault();
        table.ajax.reload();
    });
});

</script>
@endsection
