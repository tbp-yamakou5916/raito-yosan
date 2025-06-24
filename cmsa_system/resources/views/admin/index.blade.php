@extends('admin.layouts.base')

@include('admin.partials.header', [
  'bread' => [
    ['project'],
  ],
])

@section('content')

  @can('project_create')
    <div class="row mb-4">
      <div class="col-3 offset-9">
        <a class="btn btn-success btn-block" href="{{ route('admin.project.create') }}">
          {{ __('admin.project.title2') }}
          {{ __('common.add') }}
        </a>
      </div>
    </div>
  @endcan

  <div class="card">
    <div class="card-header bg-secondary">
      <span class="card-title">{{ __('admin.project._title') }}</span>
    </div>
    <div class="card-body p-0">
      <table class="table table-striped table-hover" id="DataTable">
        <thead>
        <tr>
          <td>
            {{ __('admin.id') }}
          </td>
          <td>
            {{ __('admin.master.location.title') }}
          </td>
          <td>
            {{ __('admin.project.user_id') }}
          </td>
          <td>
            {{ __('admin.project.code') }}
          </td>
          <td>
            {{ __('admin.project.title') }}
          </td>
          <td></td>
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
              {{ $datum->locationLabel ?? null }}
            </td>
            <td>
              {{ $datum->userName }}
            </td>
            <td>
              {{ $datum->code }}
            </td>
            <td>
              {{ $datum->title }}
            </td>
            <td class="text-nowrap">
              @if($now_project_id==$datum->id)
                <span class="btn btn-default btn-sm disabled">
                  選択中の{{ __('admin.project.title2') }}
                </span>
              @else
                <a href="{{ route('admin.free_form.index', ['project_id' => $datum->id]) }}" class="btn btn-warning btn-sm">
                  {{ __('admin.project.title2') }}選択
                </a>
              @endif
            </td>
            <td class="text-nowrap">
              @can('project_edit')
                {!! $datum->btnEdit !!}
              @endcan
              @can('project_destroy')
                {!! $datum->btnDestroy !!}
              @endcan
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@stop

@section('js')
  @parent
  <script>
    // dataTable設定
    let setting = {};
    $('#DataTable').DataTable(setting);
  </script>
@endsection
