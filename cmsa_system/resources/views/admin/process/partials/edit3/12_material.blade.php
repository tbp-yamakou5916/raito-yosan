<div class="card">
  <div class="card-header bg-secondary">
    <div class="c-price">
      <span class="card-title">材料費</span>
      <div class="c-price--body">
        <b>想定追加原価</b>
        <div class="bg-white px-3 c-price--body-box">
          @php($class = $material_result['result'] > 0 ? ' text-danger' : null)
          <b class="text-xl{{ $class }}">{{ $material_result['result'] }}</b>
          <b class="">円</b>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered c-table c-table-hover">
      <colgroup>
        <col style="width: 22%">
        <col style="width: 19.5%">
        <col style="width: 19.5%">
        <col style="width: 13%">
        <col style="width: 13%">
        <col style="width: 13%">
      </colgroup>
      <thead>
      <tr>
        <th></th>
        {{-- 予算金額 --}}
        <th class="text-center">
          <i class="fa-solid fa-a"></i><br>
          {{ __('admin.process_item.price.budget_price') }}
        </th>
        {{-- 予算金額×進捗率 --}}
        <th class="text-center">
          <i class="fa-solid fa-b"></i><br>
          {{ __('admin.process_item.price.budget_progress') }}
        </th>
        {{-- 実績金額 --}}
        <th class="text-center">
          <i class="fa-solid fa-c"></i><br>
          {{ __('admin.process_item.price.actual_price') }}
        </th>
        {{-- 予算との差異 --}}
        <th class="text-center">
          <i class="fa-solid fa-d"></i><br>
          {{ __('admin.process_item.price.difference') }}
        </th>
        <th class="text-center align-top">
          <i class="fa-solid fa-d"></i>
          <i class="fa-solid fa-divide"></i>
          <i class="fa-solid fa-b"></i><br>
          <i class="fa-solid fa-percent"></i>
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
            {{ $item->values['budget']['budget'] }}
          </td>
          <td class="text-right">
            {{ $item->values['budget']['progress'] }}
          </td>
          <td class="c-striped-blue text-right">
            {!! $item->values['budget']['amount'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $item->values['budget']['difference'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $item->values['budget']['diff_rate'] !!}
          </td>
        </tr>
      @endforeach
      </tbody>
      <tfoot class="c-table-foot">
      <tr>
        <th>
          材料費計
        </th>
        <td class="text-right">

        </td>
        <td class="text-right">
          {{ $material_result['progress'] }}
        </td>
        <td class="c-striped-blue text-right">
          {{ $material_result['amount'] }}
        </td>
        <td class="c-striped-blue text-right">

        </td>
        <td class="c-striped-blue text-right">
          {!! $material_result['diff'] !!}
        </td>
      </tr>
      <tr>
        <th>
          着地見込み
        </th>
        <td class="text-right">
          {{ $material_result['budget'] }}
        </td>
        <td class="text-right">

        </td>
        <td class="c-striped-blue text-right">
          {{ $material_result['amount2'] }}
        </td>
        <td class="c-striped-blue text-right">

        </td>
        <td class="c-striped-blue text-right">
          {!! $material_result['diff2'] !!}
        </td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
