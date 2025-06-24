@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['label' => __('admin.free_form.title2')],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.free_form.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    {{ html()->hidden('project_id', $project_id) }}
    @include('admin.free_form.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
