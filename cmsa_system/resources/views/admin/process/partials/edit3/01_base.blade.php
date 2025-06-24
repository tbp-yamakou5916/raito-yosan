<div class="card">
  <div class="card-header bg-info">
    <span class="card-title">作業情報</span>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered table-hover mb-0">
      <colgroup>
        <col style="width: 12.5%">
        <col style="width: 12.5%">
        <col style="width: 12.5%">
        <col style="width: 12.5%">
        <col style="width: 12.5%">
        <col style="width: 12.5%">
        <col style="width: 12.5%">
        <col style="width: 12.5%">
      </colgroup>
      <tbody>
      <tr>
        {{-- 予算数量 --}}
        <th>
          {{ __('admin.process.base.edit3.budget_quantity') }}
        </th>
        <td class="text-right">
          {!! $base_result['plan_area_label'] !!}
        </td>
        {{-- 施工完了数量 --}}
        <th>
          {{ __('admin.process.base.edit3.actual_quantity') }}
        </th>
        <td class="text-right">
          {!! $base_result['finished_area_label'] !!}
        </td>
        {{-- 作業進捗率 --}}
        <th>
          {{ __('admin.process.base.edit3.progress') }}
        </th>
        <td colspan="3" class="c-progress__frame">
          <div class="c-progress-bar" style="width: {{ $base_result['progress_rate'] }}%">
            {!! $base_result['progress_rate_label'] !!}
          </div>
        </td>
      </tr>
      <tr>
        {{-- 施工予定日数 --}}
        <th>
          {{ __('admin.process.base.edit3.budget_day') }}
        </th>
        <td class="text-right">
          {!! $base_result['plan_day_label'] !!}
        </td>
        {{-- 稼働日数 --}}
        <th>
          {{ __('admin.process.base.edit3.actual_day') }}
        </th>
        <td class="text-right">
          {!! $base_result['finished_day_label'] !!}
        </td>
        {{-- 外注基本単価 --}}
        <th>
          {{ __('admin.free_form.price.label') }}
        </th>
        <td colspan="3" class="text-center text-bold">
          {!! $base_result['base_price_label'] !!}
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
