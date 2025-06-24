@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.unit', 'master.unit.index'],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.master.unit.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.master.unit.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
