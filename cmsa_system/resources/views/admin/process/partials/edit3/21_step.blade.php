<div class="card">
  <div class="card-header bg-info">
    <span class="card-title">歩掛り</span>
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
        {{-- 実績数量 --}}
        <th class="text-center">
          <i class="fa-solid fa-c"></i><br>
          {{ __('admin.process_item.rate.actual_quantity') }}
        </th>
        {{-- 予算との差異 --}}
        <th class="text-center">
          <i class="fa-solid fa-d"></i><br>
          {{ __('admin.process_item.rate.difference') }}
        </th>
      </tr>
      </thead>
      <tbody>
      @if(!in_array($process_type, [1, 7]))
        {{-- 施工数量 --}}
        <tr>
          <th>
            {{ __('admin.process_item.step.num') }}
          </th>
          <td class="text-right">
            {!! $productivity_result['num']['budget'] !!}
          </td>
          <td class="text-right">
            {!! $productivity_result['num']['progress'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $productivity_result['num']['amount'] !!}
          </td>
          <td class="c-striped-blue text-right">

          </td>
        </tr>
      @endif
      {{-- 作業日数 --}}
      <tr>
        <th>
          {{ __('admin.process_item.step.day') }}
        </th>
        <td class="text-right">
          {!! $productivity_result['day']['budget'] !!}
        </td>
        <td class="text-right">
          {!! $productivity_result['day']['progress'] !!}
        </td>
        <td class="c-striped-blue text-right">
          {!! $productivity_result['day']['amount'] !!}
        </td>
        <td class="c-striped-blue text-right">
          {!! $productivity_result['day']['diff_rate'] !!}
        </td>
      </tr>
      {{-- 作業人工 --}}
      <tr>
        <th>
          {{ __('admin.process_item.step.man_hour') }}
        </th>
        <td class="text-right">
          {!! $productivity_result['man_hour']['budget'] !!}
        </td>
        <td class="text-right">
          {!! $productivity_result['man_hour']['progress'] !!}
        </td>
        <td class="c-striped-blue text-right">
          {!! $productivity_result['man_hour']['amount'] !!}
        </td>
        <td class="c-striped-blue text-right">
          {!! $productivity_result['man_hour']['diff_rate'] !!}
        </td>
      </tr>
      </tbody>
      @if(!in_array($process_type, [1, 7]))
        <tfoot class="c-table-foot">
        {{-- 歩掛り --}}
        <tr>
          <th>
            {{ __('admin.process_item.step.step') }}
          </th>
          <td class="text-right">
            {!! $productivity_result['step']['budget'] !!}
          </td>
          <td class="text-right">

          </td>
          <td class="c-striped-blue text-right">
            {!! $productivity_result['step']['amount'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $productivity_result['step']['diff_rate'] !!}
          </td>
        </tr>
        {{-- 歩掛り --}}
        <tr>
          <th>
            @if(in_array($process_type, [4, 5]))
              {!! __('admin.process_item.step.price.1') !!}
            @else
              {!! __('admin.process_item.step.price.2') !!}
            @endif
          </th>
          <td class="text-right">
            {!! $productivity_result['price']['budget'] !!}
          </td>
          <td class="text-right">
            {!! $productivity_result['price']['progress'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $productivity_result['price']['amount'] !!}
          </td>
          <td class="c-striped-blue text-right">
            {!! $productivity_result['price']['diff_rate'] !!}
          </td>
        </tr>
        </tfoot>
      @endif
      @if(in_array($process_type, [1]))
        <tfoot class="c-table-foot">
        @foreach($expense_items->where('cost_type', 2) as $item)
          <tr>
            <th>
              {{ $item->title }}
            </th>
            <td class="text-right">
              {!! $item->values['num']['budget'] !!}
            </td>
            <td class="text-right">
              {!! $item->values['num']['progress'] !!}
            </td>
            <td class="c-striped-blue text-right">
              {!! $item->values['num']['amount'] !!}
            </td>
            <td class="c-striped-blue text-right">
              {!! $item->values['num']['diff_rate'] !!}
            </td>
          </tr>
        @endforeach
        </tfoot>
      @endif
    </table>
  </div>
</div>
