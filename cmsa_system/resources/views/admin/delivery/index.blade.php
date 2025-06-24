@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['delivery'],
    ],
    'file' => 'list'
])

@section('content')
  <div class="row mb-4">
    <div class="col-3">
      @if($view_type)
        <a class="btn btn-default btn-block" href="{{ route('admin.delivery.index') }}">
          搬入登録
        </a>
      @else
        <span class="btn btn-info btn-block disabled">
          搬入登録
        </span>
      @endif
    </div>
    <div class="col-3">
      @if($view_type)
        <span class="btn btn-info btn-block disabled">
          在庫管理
        </span>
      @else
        <a class="btn btn-default btn-block" href="{{ route('admin.delivery.index', ['view_type' => 1]) }}">
          在庫管理
        </a>
      @endif
    </div>
    @if(!$view_type)
      <div class="col-3 offset-3">
        @if($now_project_id && $now_free_form_id)
          <a class="btn btn-success btn-block" href="{{ route('admin.delivery.create') }}">
            {{ __('common.create2') }}
          </a>
        @else
          <span class="btn btn-success btn-block disabled">
          {{ __('common.create2') }}
        </span>
        @endif
      </div>
    @endif
  </div>

  <div class="card">
    <div class="card-body p-0">
      @if($view_type)
        @include('admin.delivery.partials.table2', ['type' => 'index'])
      @else
        @include('admin.delivery.partials.table', ['type' => 'index'])
      @endif
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
