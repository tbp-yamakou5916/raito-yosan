@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['user'],
      ['user.user', 'user.user.index'],
    ],
    'file' => 'create',
])

@section('content')
    {{ html()->form()->route('admin.user.user.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.user.user.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
