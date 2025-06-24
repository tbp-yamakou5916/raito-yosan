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

@php($is_test = config('calculator.process'))

@section('content')
    {{ html()->modelForm($datum, 'PUT')->route('admin.process.update', $datum->id)->open() }}
    {{ html()->hidden('updated_by', Auth::id()) }}
    {{ html()->hidden('process_type', $process_type) }}
    {{ html()->hidden('mode_num', $mode_num) }}

    {{-- 基本情報 --}}
    @include('admin.process.partials.edit1.base')

    <div class="card js-expense" data-process-id="{{ $datum->id }}" data-cost-type="1">
      <div class="card-header bg-secondary">
        <span class="card-title">材料費</span>
      </div>
      <div class="card-body js-expense-body">
        <div class="c-grid c-grid-head c-grid--material">
          <div></div>
          <div class="text-bold text-center">@if($is_test)［IN01］@endif規格</div>
          <div class="text-bold text-center">@if($is_test)［IN02］@endif数量</div>
          <div class="text-bold text-center">@if($is_test)［IN03］@endif単価</div>
          <div class="text-bold text-center">@if($is_test)［IN04］@endifロス率</div>
          <div class="text-bold text-center">@if($is_test)［OUT01］@endif予算</div>
        </div>
        @php($item_num = 0)
        @foreach($expense_items->where('cost_type', 1) as $item)
          @include('admin.process.partials.edit1.material', ['process' => $datum, 'num' => $item_num])
          @if($is_test)
            @include('admin.process.partials.edit1.formula')
          @endif
          @php($item_num++)
        @endforeach
      </div>
      <div class="card-footer">
        <div class="row">
          {{-- 費用項目名 --}}
          @include('admin.partials.forms', [
              'type' => 'text',
              'name' => 'title',
              'trans' => 'admin.master.expense_custom_item',
              'frame' => 'col-6',
          ])
          @include('admin.partials.forms', [
              'type' => 'select',
              'name' => 'unit_type',
              'label' => 'admin.master.unit.title',
              'array' => __('array.unit_type.params'),
              'frame' => 'col-3',
              'is_empty' => true,
          ])
          <div class="col-3 no_label">
            <a class="btn btn-block bg-black text-white js-expense-btn">
              <i class="fa-solid fa-circle-plus"></i>
              材料項目追加
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="card js-expense" data-process-id="{{ $datum->id }}" data-cost-type="2">
      <div class="card-header bg-secondary">
        <span class="card-title">外注費</span>
      </div>
      <div class="card-body js-expense-body">
        <div class="c-grid c-grid-head c-grid--outsourcing">
          <div></div>
          <div class="text-bold text-center">@if($is_test)［IN11］@endif数量<br>（作業面積or作業日数）</div>
          <div class="text-bold text-center">@if($is_test)［OUT11］@endif単価</div>
          <div class="text-bold text-center">@if($is_test)［IN12］@endif想定人工</div>
          <div class="text-bold text-center">@if($is_test)［IN13］@endif作業割合/日</div>
          <div class="text-bold text-center">@if($is_test)［IN14］@endif作業人数</div>
          <div class="text-bold text-center">@if($is_test)［OUT12］@endif予算</div>
        </div>
        @foreach($expense_items->where('cost_type', 2) as $item)
          @include('admin.process.partials.edit1.outsourcing', ['process' => $datum, 'num' => $item_num])
          @if($is_test)
            @include('admin.process.partials.edit1.formula')
          @endif
          @php($item_num++)
        @endforeach
      </div>
      <div class="card-footer">
        <div class="row">
          {{-- 費用項目名 --}}
          @include('admin.partials.forms', [
              'type' => 'text',
              'name' => 'title',
              'trans' => 'admin.master.expense_custom_item',
              'frame' => 'col-6',
          ])
          @include('admin.partials.forms', [
              'type' => 'select',
              'name' => 'unit_type',
              'label' => 'admin.master.unit.title',
              'array' => __('array.unit_type.params'),
              'frame' => 'col-3',
              'is_empty' => true,
          ])
          <div class="col-3 no_label">
            <a class="btn btn-block bg-black text-white js-expense-btn">
              <i class="fa-solid fa-circle-plus"></i>
              外注費項目追加
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- フォーム番号 --}}
    {{ html()->input('hidden', 'item_num', $item_num)->class('js-expense-num') }}

    <div class="card">
      <div class="card-body">
        {{-- 作成 / 更新記録 --}}
        @include('admin.partials.form.record', ['form_type' => 'edit', 'type' => 'process'])
      </div>
    </div>

    @include('admin.partials.form.btn_submit', ['form_type' => 'edit'])
    {{ html()->closeModelForm() }}
@endsection

@section('js')
  @parent
  <script>
    const edit1Params = {
      fetchRoute: '{{ route('admin.master.expense_custom_item.api.store') }}',
      fetchRoute2: '{{ route('admin.master.expense_custom_item.api.destroy') }}',
      fetchRoute3: '{{ route('admin.process.api.calculate') }}',
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/process_edit1.js') }}"></script>
  {{-- 入力チェックアラート --}}
  <script crossorigin src="{{ asset('assets/scripts/check_input.js') }}"></script>
@endsection
