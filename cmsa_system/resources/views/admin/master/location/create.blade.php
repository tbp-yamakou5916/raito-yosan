@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.location', 'master.location.index'],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.master.location.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.master.location.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
