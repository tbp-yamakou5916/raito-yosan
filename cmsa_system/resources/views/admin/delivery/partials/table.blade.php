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
      <td class="text-right pr-4">
        {{ number_format($datum->num, 2) }}
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
