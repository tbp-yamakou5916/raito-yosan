@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.standard', 'master.standard.index'],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.master.standard.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.master.standard.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
