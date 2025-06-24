<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">基本情報</span>
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
        <th class="align-middle">
          施工期間
        </th>
        <td colspan="5">
        </td>
      </tr>
      <tr>
        {{-- 総延長 --}}
        @php($name = 'total_length')
        <th>
          {{ __('admin.process_term.' . $name) }}
        </th>
        <td>
          <div class="input-group">
            {{ html()->number('process_term[' . $name . ']', $process_term->{$name}, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">m</span>
            </div>
          </div>
        </td>
        {{-- 総面積 --}}
        @php($name = 'total_area')
        <th>
          {{ __('admin.process_term.' . $name) }}
        </th>
        <td>
          <div class="input-group">
            {{ html()->number('process_term[' . $name . ']', $process_term->{$name}, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">m&sup2;</span>
            </div>
          </div>
        </td>
        <th></th>
        <td class="text-right"></td>
      </tr>
      <tr>
        {{-- 範囲内延長 --}}
        @php($name = 'length_within')
        <th>
          {{ __('admin.process_term.' . $name) }}
        </th>
        <td>
          <div class="input-group">
            {{ html()->number('process_term[' . $name . ']', $process_term->{$name}, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">m</span>
            </div>
          </div>
        </td>
        {{-- 範囲内面積 --}}
        @php($name = 'area_within')
        <th>
          {{ __('admin.process_term.' . $name) }}
        </th>
        <td>
          <div class="input-group">
            {{ html()->number('process_term[' . $name . ']', $process_term->{$name}, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">m&sup2;</span>
            </div>
          </div>
        </td>
        {{-- 進捗率 --}}
        @php($name = 'rate')
        <th>
          {{ __('admin.process_term.' . $name) }}
        </th>
        <td>
          <div class="input-group">
            {{ html()->number('process_term[' . $name . ']', $process_term->{$name}, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">m</span>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        {{-- 施工終了総延長 --}}
        @php($name = 'total_length_after')
        <th>
          {{ __('admin.process_term.' . $name) }}
        </th>
        <td>
          <div class="input-group">
            {{ html()->number('process_term[' . $name . ']', $process_term->{$name}, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">m</span>
            </div>
          </div>
        </td>
        {{-- 施工終了総面積 --}}
        @php($name = 'total_area_after')
        <th>
          {{ __('admin.process_term.' . $name) }}
        </th>
        <td>
          <div class="input-group">
            {{ html()->number('process_term[' . $name . ']', $process_term->{$name}, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">m&sup2;</span>
            </div>
          </div>
        </td>
        {{-- 全体進捗率 --}}
        @php($name = 'overall_rate')
        <th>
          {{ __('admin.process_term.' . $name) }}
        </th>
        <td>
          <div class="input-group">
            {{ html()->number('process_term[' . $name . ']', $process_term->{$name}, 0, null, 0.01)->class($class) }}
            <div class="input-group-append">
              <span class="input-group-text">m</span>
            </div>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
