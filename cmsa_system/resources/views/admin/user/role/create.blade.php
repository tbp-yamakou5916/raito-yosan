@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.role', 'user.role.index'],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.user.role.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.user.role.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
