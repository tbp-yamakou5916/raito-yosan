@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.ff_category', 'master.ff_category.index'],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.master.ff_category.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.master.ff_category.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
