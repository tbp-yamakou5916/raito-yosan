@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.user', 'user.user.index'],
    ],
    'file' => 'show'
])

@section('content')
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped table-hover table-sm">
                <colgroup>
                    <col style="width: 30%;">
                    <col style="width: 70%;">
                </colgroup>
                <tbody>
                <tr>
                    <th>
                        {{ __('admin.id') }}
                    </th>
                    <td>
                        {{ $datum->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('admin.user.user.name') }}
                    </th>
                    <td>
                        {{ $datum->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('admin.user.user.email') }}
                    </th>
                    <td>
                        {{ $datum->email }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('admin.invalid') }}
                    </th>
                    <td>
                        {{ $datum->invalid_label }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('admin.user.user.roles') }}
                    </th>
                    <td>
                        @foreach($datum->roles as $role)
                            <span class="badge badge-{{ $role->color }}">{{ $role->ja }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('admin.created_by') }}
                    </th>
                    <td>
                        {{-- app/Models/Traits/FootPrint.php --}}
                        {!! $datum->created_html !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('admin.updated_by') }}
                    </th>
                    <td>
                        {{-- app/Models/Traits/FootPrint.php --}}
                        {!! $datum->updated_html !!}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer row">
            @php($datum->is_block = true)
            <div class="col-2">
                <a class="btn btn-block btn-default" href="{{ route('admin.user.user.index') }}">
                    {{ __('common.back_to_list') }}
                </a>
            </div>
            <div class="col-2 offset-3">
                @can('user_edit')
                    {!! $datum->btn_edit !!}
                @endcan
            </div>
            <div class="col-2 offset-3">
                @can('user_destroy')
                    {!! $datum->btn_destroy !!}
                @endcan
            </div>
        </div>
    </div>
@endsection
