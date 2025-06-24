@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.user'],
    ],
])

@section('content')
  @role('system_admin')
  <div class="callout callout-warning">
    <h5>システム管理者のみ表示されています</h5>
    <p>システム管理者以外の人には、システム管理者は表示されません</p>
  </div>
  @endrole

  @can('user_create')
    <div class="row mb-4">
      <div class="col-3 offset-9">
        <a class="btn btn-success btn-block" href="{{ route('admin.user.user.create') }}">
          {{ __('common.add') }}
        </a>
      </div>
    </div>
  @endcan

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-striped table-hover" id="DataTable">
        <thead>
        <tr>
          <td>
            {{ __('admin.id') }}
          </td>
          <td>
            {{ __('admin.user.user.location_id') }}
          </td>
          <td>
            {{ __('admin.user.user.name') }}
          </td>
          <td>
            {{ __('admin.user.user.email') }}
          </td>
          <td>
            {{ __('admin.user.user.roles') }}
          </td>
          <td>
            {{ __('admin.created_by') }}
          </td>
          <td>
            {{ __('admin.updated_by') }}
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
              {{ $datum->locationLabel }}
            </td>
            <td>
              {{ $datum->name }}
            </td>
            <td>
              {{ $datum->email }}
            </td>
            <td>
              @foreach($datum->roles->sortBy('sequence') as $role)
                <span class="badge badge-{{ $role->color }}">
                                    {{ $role->ja }}
                                </span>
              @endforeach
            </td>
            <td>
              {!! $datum->created_html !!}
            </td>
            <td>
              {!! $datum->updated_html !!}
            </td>
            <td class="text-nowrap">
              {!! $datum->btn_show !!}
              @can('user_edit')
                {!! $datum->btn_edit !!}
              @endcan
              @can('user_destroy')
                {!! $datum->btn_destroy !!}
              @endcan
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
