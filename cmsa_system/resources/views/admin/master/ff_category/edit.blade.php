@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.ff_category', 'master.ff_category.index'],
    ],
    'file' => 'edit'
])

@section('content')
    {{ html()->modelForm($datum, 'PUT')->route('admin.master.ff_category.update', $datum->id)->open() }}
    {{ html()->hidden('updated_by', Auth::id()) }}
    @include('admin.master.ff_category.form', ['form_type' => 'edit'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
    {{ html()->closeModelForm() }}
@endsection
