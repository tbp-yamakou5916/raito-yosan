@extends('admin.layouts.error')
@php($bread=[])
@php($page = __('errors.400'))
@section('head_params')
    @php($title = $page['h1'])
    @php($plus_default = false)
    @php($is_error = true)
@endsection
@include('errors.content')
