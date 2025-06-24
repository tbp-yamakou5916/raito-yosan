<table class="table table-striped table-hover" id="DataTable">
  <thead>
  <tr>
    <td>
      {{ __('admin.process.title') }}
    </td>
    @if(!$view_type)
      <td>
        {{ __('admin.delivery.delivered_at') }}
      </td>
    @endif
    <td>
      {{ __('admin.delivery.name') }}
    </td>
    <td>
      {{ __('admin.master.standard.title') }}
    </td>
    <td class="col-1">
      {{ __('admin.delivery.num') }}
    </td>
    <td class="col-1">
      {{ __('admin.delivery.num2') }}
    </td>
    <td class="col-1">
      {{ __('admin.delivery.num3') }}
    </td>
    <td></td>
  </tr>
  </thead>
  <tbody>
  @foreach($data as $datum)
    <tr class="{{ $datum->tr_class }}">
      <td>
        <a href="{{ route('admin.process.edit', 'process' . $datum->processType) }}">
          {{ $datum->processLabel }}
        </a>
      </td>
      @if(!$view_type)
        <td>
          {{ $datum->deliveredAtLabel }}
        </td>
      @endif
      <td>
        {{ $datum->title }}
      </td>
      <td>
        {{ $datum->standardLabel }}
      </td>
      {{-- 搬入数量（累積） --}}
      @php($delivery_sum = $datum->deliverySum)
      @php($class = $delivery_sum==0 ? ' text-muted' : null)
      <td class="text-right pr-4{{ $class }}">
        {{ number_format($delivery_sum, 2) }}
        {!! $datum->unitHtml !!}
      </td>
      {{-- 使用数量（累積） --}}
      @php($usage_sum = $datum->usageSum)
      @php($class = $usage_sum==0 ? ' text-muted' : null)
      <td class="text-right pr-4{{ $class }}">
        {{ number_format($usage_sum, 2) }}
        {!! $datum->unitHtml !!}
      </td>
      {{-- 残量 --}}
      @php($diff = $delivery_sum - $usage_sum)
      @php($class = null)
      @if($diff < 0)
        @php($class = 'text-danger text-bold')
      @elseif($diff > 0)
        @php($class = 'text-info text-bold')
      @else
        @php($class = 'text-muted')
      @endif
      <td class="text-right pr-4 {{ $class }}">
        {{ number_format($delivery_sum - $usage_sum, 2) }}
        {!! $datum->unitHtml !!}
      </td>
      <td class="text-nowrap">
        @if($view_type)
          <a class="btn btn-success btn-sm" href="{{ route('admin.delivery.add', $datum->id) }}">
            {{ __('common.add') }}
          </a>
        @else
          {!! $datum->btnEdit !!}
        @endif
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
