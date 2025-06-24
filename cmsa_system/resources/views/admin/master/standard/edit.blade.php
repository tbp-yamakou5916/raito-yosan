@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.standard', 'master.standard.index'],
    ],
    'file' => 'edit'
])

@section('content')
    {{ html()->modelForm($datum, 'PUT')->route('admin.master.standard.update', $datum->id)->open() }}
    {{ html()->hidden('updated_by', Auth::id()) }}
    @include('admin.master.standard.form', ['form_type' => 'edit'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
    {{ html()->closeModelForm() }}
@endsection
