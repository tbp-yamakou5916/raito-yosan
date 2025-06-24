@extends('admin.layouts.base')

@include('admin.partials.header', [
  'h1_title' => __('admin.mode1._menu'),
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
  @if($process_type != 'all')
    @include('admin.process.partials.edit1.01_base')
  @else
    <div class="row py-4">
      <div class="col-3">
        <a class="btn btn-block bg-black text-white">
          <i class="fa-solid fa-file-import"></i>
          RING4インポート（未実装）
        </a>
      </div>
    </div>
  @endif

  {{-- 材料費 --}}
  @php($item_num = 0)
  @include('admin.process.partials.edit1.02_material_wrapper')

  @if($process_type == 'all')
    {{-- 更新ボタン --}}
    @include('admin.process.partials.edit1.button')
  @endif

  {{-- 外注費 --}}
  @php($item_num = 100)
  @include('admin.process.partials.edit1.03_outsourcing_wrapper')

  @if($process_type != 'all')
    <div class="card">
      <div class="card-body">
        {{-- 作成 / 更新記録 --}}
        @include('admin.partials.form.record', ['form_type' => 'edit', 'type' => 'process'])
      </div>
    </div>
  @endif

  {{-- 更新ボタン --}}
  @include('admin.process.partials.edit1.button')

  {{ html()->closeModelForm() }}
@endsection

@section('js')
  @parent
  <script>
    const edit1Params = {
      isFormula: @json(config('calculator.process')),
      fetchRoute: '{{ route('admin.master.expense_custom_item.api.store') }}',
      fetchRoute2: '{{ route('admin.master.expense_custom_item.api.destroy') }}',
      fetchRoute3: '{{ route('admin.process.api.calculate') }}',
    }
  </script>
  <script crossorigin src="{{ asset('assets/scripts/process_edit1.js') }}"></script>
  {{-- 入力チェックアラート --}}
  <script crossorigin src="{{ asset('assets/scripts/check_input.js') }}"></script>
@endsection
