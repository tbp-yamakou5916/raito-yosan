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
      <div class="text-bold text-center">@if($is_test)［OUT01］@endif予算金額</div>
      <div class="text-bold text-center">@if($is_test)［IN04］@endifロス率</div>
    </div>
    @php($item_process_type = 0)
    @foreach($expense_items->where('cost_type', 1) as $item)
      {{-- 工程タイトル --}}
      @if($process_type == 'all')
        @if($item->process_type != $item_process_type)
          @php($process = $process_nav->where('process_type', $item->process_type)->first())
          <div class="c-grid-title">
            <div class="c-grid-title--main">
              {{ $item->processTypeLabel }}
            </div>
            <div>
              @if($process)
                {{ $process->constructionPeriod }}（{{ $process->schedule_day_label }}）
              @endif
            </div>
          </div>
          @php($item_process_type = $item->process_type)
        @endif
      @endif
      @include('admin.process.partials.edit1.material', [
        'process' => $datum,
        'num' => $item_num
      ])
      @if($is_test)
        @include('admin.process.partials.edit1.formula')
      @endif
      @php($item_num++)
    @endforeach
  </div>
  @if($process_type != 'all')
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
            'name' => 'unit_id',
            'label' => 'admin.master.unit.title',
            'array' => $units,
            'frame' => 'col-3',
            'is_empty' => true,
        ])
        <div class="col-3 no_label">
          <a class="btn btn-block bg-black text-white js-expense-btn js-check-ignore">
            <i class="fa-solid fa-circle-plus"></i>
            材料項目追加
          </a>
        </div>
      </div>
    </div>
  @endif
</div>
