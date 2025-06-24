@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['delivery', 'delivery.index'],
    ],
    'file' => 'create2'
])

@section('content')
  {{ html()->form()->route('admin.delivery.store')->open() }}
  {{ html()->hidden('created_by', Auth::id()) }}
  {{ html()->hidden('view_type', $view_type ?? 0) }}
  @include('admin.delivery.form', ['form_type' => 'create'])
  @include('admin.partials.form.btn_submit', ['form_type' => 'create', 'type' => 'delivery'])
  {{ html()->form()->close() }}
@endsection
