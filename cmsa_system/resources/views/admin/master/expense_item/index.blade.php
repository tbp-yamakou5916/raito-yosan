@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.expense_item'],
    ],
])

@section('content')
  <div class="row mb-4">
    <div class="col-3 offset-9">
      <a class="btn btn-success btn-block" href="{{ route('admin.master.expense_item.create') }}">
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
            {{ __('admin.id') }}
          </td>
          <td>
            {{ __('admin.sequence') }}
          </td>
          <td>
            {{ __('array.process_type.label') }}
          </td>
          <td>
            {{ __('array.cost_type.label') }}
          </td>
          <td>
            {{ __('admin.master.expense_item.title') }}
          </td>
          <td>
            {{ __('admin.master.unit.title') }}
          </td>
          <td></td>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $datum)
          <tr class="{{ $datum->tr_class }}">
            <td>
              {{ $datum->id }}
            </td>
            <td>
              {{ $datum->sequence }}
            </td>
            <td>
              {{ $datum->processTypeLabel }}
            </td>
            <td>
              {{ $datum->costTypeLabel }}
            </td>
            <td>
              {{ $datum->title }}
            </td>
            <td>
              {!! $datum->unitHtml !!}
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
