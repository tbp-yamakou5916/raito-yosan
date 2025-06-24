@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['delivery', 'delivery.index'],
    ],
    'file' => 'edit'
])

@section('content')
  {{ html()->modelForm($datum, 'PUT')->route('admin.delivery.update', $datum->id)->open() }}
  {{ html()->hidden('updated_by', Auth::id()) }}
  @include('admin.delivery.form', ['form_type' => 'edit'])
  @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
  {{ html()->closeModelForm() }}
@endsection
