{{-- パンクズ設定 --}}
@php($bread = collect($bread ?? Null))
{{-- ファイルタイプ --}}
@php($file = $file ?? Null)

@php($arr = [])
@php($page_h1_title = $h1_title ?? null)
@php($h1_title = null)

@section('breadcrumb')
  @if($bread->isNotEmpty())
    @foreach($bread as $item)
      <li class="breadcrumb-item @if(!$file && $loop->last) active @endif">
        @isset($item['label'])
          @php($label = $item['label'])
        @else
          @php($label = __('admin.' . ($item[0] ? $item[0] . '.' : '') . '_menu'))
        @endisset
        @php($arr[] = $label)
        @isset($item[1])
          @if(is_array($item[1]))
            <a href="{{ route('admin.' . $item[1][0], [$item[1][1]]) }}">
              {{ $label }}
            </a>
          @else
            <a href="{{ route('admin.' . $item[1]) }}">
              {{ $label }}
            </a>
          @endif
        @else
          {{ $label }}
        @endif
        @php($h1_title = $label)
      </li>
      @if($file && $loop->last)
        @php($file_name = __('common.' . $file))
        <li class="breadcrumb-item active">
          {{ $file_name }}
        </li>
        @php(array_push($arr, $file_name))
        @php($h1_title .= ' ' . $file_name)
      @endif
    @endforeach
  @endif
@stop

{{-- H1タイトル --}}
@if($page_h1_title)
  @php($h1_title = $page_h1_title)
@endif
@section('h1_title', $h1_title)

{{-- ページタイトル --}}
{{--
@php(rsort($arr))
@section('title', implode(' | ', $arr))
--}}
