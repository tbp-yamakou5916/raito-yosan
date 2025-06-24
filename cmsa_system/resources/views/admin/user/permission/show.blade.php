@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.permission', 'user.permission.index'],
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
                        {{ __('admin.user.permission.name') }}
                    </th>
                    <td>
                        {{ $datum->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('admin.user.permission.ja') }}
                    </th>
                    <td>
                        <span class="badge badge-{{ $datum->color }}">{{ $datum->ja }}</span>
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ __('admin.sequence') }}
                    </th>
                    <td>
                        {{ $datum->sequence }}
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
            {{-- ボタン表示形式変更用 --}}
            @php($datum->is_block = true)
            <div class="col-2">
                <a class="btn btn-block btn-default" href="{{ route('admin.user.permission.index') }}">
                    {{ __('common.back_to_list') }}
                </a>
            </div>
            <div class="col-2 offset-3">
                {!! $datum->btn_edit !!}
            </div>
            <div class="col-2 offset-3">
                {!! $datum->btn_destroy !!}
            </div>
        </div>
    </div>
@endsection
