@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.role'],
    ],
])

@section('content')
    @role('system_admin')
    <div class="callout callout-warning">
        <h5>システム管理者のみ表示されています</h5>
    </div>
    @endrole

    <div class="row mb-4">
        <div class="col-3 offset-9">
            <a class="btn btn-success btn-block" href="{{ route('admin.user.role.create') }}">
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
                        {{ __('admin.user.role.name') }}
                    </td>
                    <td>
                        {{ __('admin.user.role.ja') }}
                    </td>
                    <td>
                        {{ __('admin.user.role.permissions') }}
                    </td>
                    <td>
                        {{ __('admin.sequence') }}
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
                            {{ $datum->name }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $datum->color }}">
                                {{ $datum->ja ?? '' }}
                            </span>
                        </td>
                        <td>
                            @foreach($datum->permissions->sortBy('sequence') as $permission)
                                <span class="badge badge-{{ $permission->color }}">
                                    {{ $permission->ja }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            {{ $datum->sequence ?? '' }}
                        </td>
                        <td>
                            {!! $datum->created_html !!}
                        </td>
                        <td>
                            {!! $datum->updated_html !!}
                        </td>
                        <td class="text-nowrap">
                            {!! $datum->btn_show !!}
                            {!! $datum->btn_edit !!}
                            {!! $datum->btn_destroy !!}
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
