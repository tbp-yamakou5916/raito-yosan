@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.ff_default'],
    ],
])

@section('content')
  <div class="row mb-4">
    <div class="col-3 offset-9">
      <a class="btn btn-success btn-block" href="{{ route('admin.master.ff_default.create') }}">
        {{ __('common.add') }}
      </a>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-striped table-hover" id="DataTable">
        <thead>
        <tr>
          <td>
            {{ __('admin.master.ff_category.title') }}
          </td>
          <td>
            {{ __('admin.master.expense_item.title') }}
          </td>
          <td>
            {{ __('admin.master.standard.title') }}
          </td>
          <td></td>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $datum)
          <tr class="{{ $datum->tr_class }}">
            <td>
              {{ $datum->ffCategoryLabel }}
            </td>
            <td>
              {{ $datum->expenseItemLabel }}
            </td>
            <td>
              {{ $datum->standardLabel }}
            </td>
            <td class="text-nowrap">
              {!! $datum->btnEdit !!}
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('js')
  @parent
  <script>
    // dataTable設定
    let setting = {};
    $('#DataTable').DataTable(setting);
  </script>
@endsection
