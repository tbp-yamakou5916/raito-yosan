@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.role', 'user.role.index'],
    ],
    'file' => 'edit'
])

@section('content')
    {{ html()->modelForm($datum, 'PUT')->route('admin.user.role.update', $datum->id)->open() }}
    {{ html()->hidden('updated_by', Auth::id()) }}
    @include('admin.user.role.form', ['form_type' => 'edit'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
    {{ html()->closeModelForm() }}
@endsection
