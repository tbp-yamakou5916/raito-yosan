<div class="card">
  <div class="card-header bg-secondary">
    <div class="c-price">
      <span class="card-title">外注費</span>
      <div class="c-price--body">
        <b>想定追加原価</b>
        <div class="bg-white px-3 c-price--body-box">
          @php($class = $outsourcing_result['result'] > 0 ? ' text-danger' : null)
          <b class="text-xl{{ $class }}">{{ $outsourcing_result['result'] }}</b>
          <b class="">円</b>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered c-table c-table-hover c-striped">
      <colgroup>
        <col style="width: 22%">
        <col style="width: 19.5%">
        <col style="width: 19.5%">
        <col style="width: 19.5%">
        <col style="width: 19.5%">
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
      </tr>
      </thead>
      @php($class = null)
      @if(in_array($process_type, [1]))
        <tbody>
        @foreach($expense_items->where('cost_type', 2) as $item)
          <tr>
            <th>
              {{ $item->title }}
            </th>
            <td class="text-right">
              {!! $item->values['budget']['budget'] !!}
            </td>
            <td class="text-right">
              {!! $item->values['budget']['progress'] !!}
            </td>
            <td class="c-striped-blue text-right">
              {!! $item->values['budget']['amount'] !!}
            </td>
            <td class="c-striped-blue text-right">
              {!! $item->values['budget']['diff_rate'] !!}
            </td>
          </tr>
        @endforeach
        </tbody>
        @php($class = 'c-table-foot')
      @endif
      <tfoot class="{{ $class }}">
      <tr>
        <th>
          外注費
        </th>
        <td class="text-right">
          {!! $outsourcing_result['budget'] !!}
        </td>
        <td class="text-right">
          {!! $outsourcing_result['progress'] !!}
        </td>
        <td class="c-striped-blue text-right">
          {!! $outsourcing_result['amount'] !!}
        </td>
        <td class="c-striped-blue text-right">
          {!! $outsourcing_result['diff'] !!}
        </td>
      </tr>
      <tr>
        <th>
          着地見込み
        </th>
        <td class="text-right">
          {!! $outsourcing_result['budget'] !!}
        </td>
        <td class="text-right">

        </td>
        <td class="c-striped-blue text-right">
          {!! $outsourcing_result['amount2'] !!}
        </td>
        <td class="c-striped-blue text-right">
          {!! $outsourcing_result['diff2'] !!}
        </td>
      </tr>
      </tfoot>
    </table>
  </div>
</div>
