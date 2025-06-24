@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['label' => __('admin.free_form.title2')],
    ],
    'file' => 'edit'
])

@section('content')
    {{ html()->modelForm($datum, 'PUT')->route('admin.free_form.update', $datum->id)->open() }}
    {{ html()->hidden('updated_by', Auth::id()) }}
    @include('admin.free_form.form', ['form_type' => 'edit'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
    {{ html()->closeModelForm() }}
@endsection
