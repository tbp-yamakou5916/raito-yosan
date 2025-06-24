@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['label' => __('admin.project.title2')],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.project.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.project.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
