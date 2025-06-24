@php($class = ['form-control', 'text-right'])
<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">作業情報</span>
  </div>
  <div class="card-body p-0">
    <table class="table table-bordered table-hover">
      <colgroup>
        <col class="col-2">
        <col class="col-2">
        <col class="col-2">
        <col class="col-2">
        <col class="col-2">
        <col class="col-2">
      </colgroup>
      <tbody>
      <tr>
        {{-- 施工期間 --}}
        <th class="align-middle">
          {{ __('admin.process.base.edit2.construction_term') }}
        </th>
        <td colspan="5" class="text-lg">
          {{ $process_term->constructionTermLabel }}
        </td>
      </tr>
      <tr>
        {{-- 稼働日数 --}}
        <th class="align-middle">
          {{ __('admin.process.base.edit2.actual_day') }}
        </th>
        <td>
          <div class="input-group">
            @php($f_name = 'real_day')
            {{--
            @php($class2 = array_merge($class, ['js-rate']))
            --}}
            {{ html()->number('process_term[' . $f_name . ']', $process_term->{$f_name} ?? 0, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">日</span>
            </div>
          </div>
        </td>
        {{-- 期間内施工人工 --}}
        <th class="align-middle">
          {{ __('admin.process.base.edit2.man_hour') }}
        </th>
        <td>
          <div class="input-group c-output">
            @php($class2 = array_merge($class, ['js-term-man-hour']))
            {{--
            @php($class2 = array_merge($class, ['js-rate']))
            --}}
            <div class="{{ implode(' ', $class2) }}">
              {{ $man_hour }}
            </div>
            <div class="input-group-append">
              <span class="input-group-text">人工</span>
            </div>
            {{--
            @php($f_name = 'man_hour')
            @php($class2 = array_merge($class, ['js-rate']))
            {{ html()->number('process_term[' . $f_name . ']', $process_term->{$f_name} ?? 0, 0, null, 0.01)->class($class2) }}
            <div class="input-group-append">
              <span class="input-group-text">人工</span>
            </div>
            --}}
          </div>
        </td>
        <td colspan="2"></td>
      </tr>
      @if(!in_array($process_type, [1, 2, 7]))
        <tr>
          {{-- 総延長 --}}
          <th>
            {{ __('admin.process_term.total_length') }}
          </th>
          <td class="text-right">
            {{ $process_term->totalLengthLabel ?? '-' }}
          </td>
          {{-- 総面積 --}}
          <th>
            {{ __('admin.process_term.total_area') }}
          </th>
          <td class="text-right">
            {{ $process_term->totalAreaLabel ?? '-' }}
          </td>
          {{-- 施工予定日数 --}}
          <th>
            {{ __('admin.process.schedule_day') }}
          </th>
          <td class="text-right">
            {{ $datum->scheduleDayLabel }}
          </td>
        </tr>
        <tr>
          {{-- 範囲内延長 --}}
          <th>
            {{ __('admin.process_term.length_within') }}
          </th>
          <td class="text-right">
            {{ $process_term->lengthWithinLabel ?? '-' }}
          </td>
          {{-- 範囲内面積 --}}
          <th>
            {{ __('admin.process_term.area_within') }}
          </th>
          <td class="text-right">
            {{ $process_term->areaWithinLabel ?? '-' }}
          </td>
          {{-- 進捗率 --}}
          <th>
            {{ __('admin.process_term.rate') }}
          </th>
          <td class="text-right">
            {{ $process_term->rateLabel ?? '-' }}
          </td>
        </tr>
        <tr>
          {{-- 施工終了総延長 --}}
          <th>
            {{ __('admin.process_term.total_length_after') }}
          </th>
          <td class="text-right">
            {{ $process_term->totalLengthAfterLabel ?? '-' }}
          </td>
          {{-- 施工終了総面積 --}}
          <th>
            {{ __('admin.process_term.total_area_after') }}
          </th>
          <td class="text-right">
            {{ $process_term->totalAreaAfterLabel ?? '-' }}
          </td>
          {{-- 全体進捗率 --}}
          <th>
            {{ __('admin.process_term.overall_rate') }}
          </th>
          <td class="text-right">
            {{ $process_term->overallRateLabel ?? '-' }}
          </td>
        </tr>
      @endif
      </tbody>
    </table>
  </div>
</div>
