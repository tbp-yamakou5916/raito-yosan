@php($class = $mode_num==1 ? ' --plus' : null)
<div class="content-fluid c-header-nav{{ $class }}">
  @if($mode_num==1)
    @php($color = $process_type == 'all' ? 'primary' : 'default')
    <div class="c-header-nav__item">
      <a href="{{ route('admin.process.edit', 'all') }}" class="btn btn-{{ $color }} btn-block">
        全工程費用項目
      </a>
    </div>
  @endif
  @foreach($process_nav as $process)
    @php($color = $process_type == $process->process_type ? 'primary' : 'default')
    <div class="c-header-nav__item">
      <a href="{{ route('admin.process.edit', 'process' . $process->process_type) }}" class="btn btn-{{ $color }} btn-block">
        {{ __('array.process_type.params.' . $process->process_type) }}
      </a>
    </div>
  @endforeach
</div>
