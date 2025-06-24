@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['master'],
      ['master.expense_item', 'master.expense_item.index'],
    ],
    'file' => 'create'
])

@section('content')
    {{ html()->form()->route('admin.master.expense_item.store')->open() }}
    {{ html()->hidden('created_by', Auth::id()) }}
    @include('admin.master.expense_item.form', ['form_type' => 'create'])
    @include('admin.partials.form.btn_submit', ['form_type' => 'create'])
    {{ html()->form()->close() }}
@endsection
