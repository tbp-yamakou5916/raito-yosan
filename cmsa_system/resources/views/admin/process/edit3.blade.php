@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['process', 'process.index'],
    ],
    'file' => 'edit',
    'h1_title' => $datum->processLabel,
])

@section('content_header')
  @parent
  {{-- 工程メニュー --}}
  @include('admin.process.partials.process_nav')
@endsection

@php($class = ['form-control'])

@section('content')
  {{ html()->modelForm($datum, 'PUT')->route('admin.process.update', $datum->id)->open() }}
  {{ html()->hidden('updated_by', Auth::id()) }}
  {{ html()->hidden('process_type', $process_type) }}
  {{ html()->hidden('mode_num', $mode_num) }}

  @php($reason = $datum->comments->where('mode', $mode_num))

  {{-- 作業情報 --}}
  @include('admin.process.partials.edit3.01_base')

  {{-- 材料ロス --}}
  @include('admin.process.partials.edit3.11_material_loss')

  {{-- 材料費 --}}
  @include('admin.process.partials.edit3.12_material')

  {{-- 乖離理由 --}}
  @include('admin.process.partials.edit3.comment', ['cost_type' => 1])
  {{--
  @include('admin.process.partials.edit3.reason', ['num' => 1])
  @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
  --}}


  {{-- 歩掛り --}}
  @include('admin.process.partials.edit3.21_step')

  {{-- 外注費 --}}
  @include('admin.process.partials.edit3.22_outsourcing')

  {{-- 乖離理由 --}}
  @include('admin.process.partials.edit3.comment', ['cost_type' => 2])
  {{--
  @include('admin.process.partials.edit3.reason', ['num' => 2])
  @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
  --}}

  {{ html()->closeModelForm() }}
@endsection
