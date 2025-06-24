<div class="card">
  <div class="card-header bg-info">
    <span class="card-title">材料ロス</span>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered c-table-hover c-striped">
      <colgroup>
        <col style="width: 22%">
        <col style="width: 13%">
        <col style="width: 13%">
        <col style="width: 13%">
        <col style="width: 13%">
        <col style="width: 13%">
        <col style="width: 13%">
      </colgroup>
      <thead>
      <tr>
        <th></th>
        {{-- 予算数量 --}}
        <th class="text-center">
          <i class="fa-solid fa-a"></i><br>
          {{ __('admin.process_item.rate.budget_quantity') }}
        </th>
        {{-- 予算数量×進捗率 --}}
        <th class="text-center">
          <i class="fa-solid fa-b"></i><br>
          {{ __('admin.process_item.rate.budget_progress') }}
        </th>
        {{-- 予算ロス率 --}}
        <th class="text-center">
          <i class="fa-solid fa-c"></i><br>
          {{ __('admin.process_item.rate.budget_rate') }}
        </th>
        {{-- 実績数量 --}}
        <th class="text-center">
          <i class="fa-solid fa-d"></i><br>
          {{ __('admin.process_item.rate.actual_quantity') }}
        </th>
        {{-- 実績ロス率 --}}
        <th class="text-center">
          <i class="fa-solid fa-e"></i><br>
          {{ __('admin.process_item.rate.actual_rate') }}
        </th>
        {{-- 予算との差異 --}}
        <th class="text-center">
          <i class="fa-solid fa-e"></i>
          <i class="fa-solid fa-minus"></i>
          <i class="fa-solid fa-c"></i><br>
          {{ __('admin.process_item.rate.difference') }}
        </th>
      </tr>
      </thead>
      <tbody>
      @foreach($expense_items->where('cost_type', 1) as $item)
        <tr>
          <th>
            {{ $item->title }}
          </th>
          <td class="text-right">
            {!! $item->values['num']['num'] !!}
          </td>
          <td class="text-right">
            {!! $item->values['num']['progress'] !!}
          </td>
          <td class="text-right">
            {!! $item->values['num']['loss_rate'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $item->values['num']['amount'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $item->values['num']['los_rate2'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $item->values['num']['diff_rate'] !!}
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
