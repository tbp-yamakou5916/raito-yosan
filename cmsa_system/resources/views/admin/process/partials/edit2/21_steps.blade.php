<div class="card">
  <div class="card-header bg-secondary">
    <span class="card-title">歩掛かり</span>
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
      @php($unit = 'm&sup2;/人･日')
      @if(in_array($process_type, [4, 5]))
        @php($unit = 'm/人･日')
      @endif
      <tr>
        <th>
          期間内歩掛かり
        </th>
        <td class="text-right">
          <span class="js-rate-term">{{ $term_rate }}</span> {!! $unit !!}
        </td>
        <th>
          累計歩掛かり
        </th>
        <td class="text-right">
          <span class="js-rate-total">{{ $total_rate }}</span> {!! $unit !!}
        </td>
        <th>

        </th>
        <td class="text-right">

        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>
