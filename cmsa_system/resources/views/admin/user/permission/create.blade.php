@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.permission', 'user.permission.index'],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.user.permission.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.user.permission.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
