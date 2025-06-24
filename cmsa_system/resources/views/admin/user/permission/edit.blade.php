@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.permission', 'user.permission.index'],
    ],
    'file' => 'edit'
])

@section('content')
    {{ html()->modelForm($datum, 'PUT')->route('admin.user.permission.update', $datum->id)->open() }}
    {{ html()->hidden('updated_by', Auth::id()) }}
    @include('admin.user.permission.form', ['form_type' => 'edit'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
    {{ html()->closeModelForm() }}
@endsection
