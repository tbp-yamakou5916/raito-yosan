@extends('admin.layouts.base')

@include('admin.partials.header', [
    'bread' => [
      ['process', 'process.index'],
    ],
    'file' => 'edit',
    'h1_title' => $datum->processLabel,
])

@php($class = ['form-control'])

@section('content')
  {{-- ファイルアップロード --}}
  {{--
  @include('admin.csv.partials.upload', ['type' => 'process'])
  --}}

  {{ html()->modelForm($datum, 'PUT')->route('admin.process.update', $datum->id)->open() }}
  {{ html()->hidden('updated_by', Auth::id()) }}
  {{ html()->hidden('process_type', $process_type) }}
  {{ html()->hidden('mode_num', $mode_num) }}
  {{ html()->hidden('process_term_id', $process_term->id) }}

  {{-- 作業情報 --}}
  @include('admin.process.partials.edit2.01_base')

  {{-- 材料費 --}}
  @include('admin.process.partials.edit2.31_material')

  {{-- コメント --}}
  @include('admin.process.partials.edit2.41_comment_wrapper', ['cost_type' => 1])


  @if($expense_items->where('cost_type', 2)->isNotEmpty())
    {{-- 外注費 --}}
    @include('admin.process.partials.edit2.32_outsourcing')

    {{-- 歩掛かり --}}
    {{--
    @include('admin.process.partials.edit2.21_steps')
    --}}

    {{-- コメント --}}
    @include('admin.process.partials.edit2.41_comment_wrapper', ['cost_type' => 2])
  @endif

  {{-- 登録済み施工期間 --}}
  @include('admin.process.partials.edit2.51_process_terms')

  <div class="row py-3">
    {{ html()->hidden('is_mode_change', 0) }}
    <div class="col-6 offset-3">
      {{ html()->button(__('common.update'), 'submit')->class(['btn', 'btn-info', 'btn-block']) }}
    </div>
    <div class="col-3">
      {{ html()->button(__('admin.mode3._menu'), 'submit')->class(['btn', 'btn-warning', 'btn-block', 'js-change-mode']) }}
    </div>
  </div>
  {{ html()->closeModelForm() }}
@endsection

@section('js')
  @parent
  <script>
    const edit2Params = {
      fetchRoute: '{{ route('admin.process.api.addCommentForm') }}',
      fetchRoute2: '{{ route('admin.process.api.getRate') }}',
      fetchRoute3: '{{ route('admin.process.api.materialCalculate') }}',
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/process_edit2.js') }}"></script>
@endsection
