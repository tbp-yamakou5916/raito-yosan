@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.ff_default', 'master.ff_default.index'],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.master.ff_default.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.master.ff_default.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
