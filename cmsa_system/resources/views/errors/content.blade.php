@section('title', $page['title'])

@section('page_params')
  @php($is_error = true)
@endsection

@section('content')
  <h2 class="c-title --second u-mt-lg">
    {{ $page['subtitle'] }}
  </h2>
  <p class="u-mt-sm">
    {!! nl2br($page['comment']) !!}
  </p>
  <p class="u-mt-md">
    <a href="{{ route('admin.index') }}" class="c-link --underline">トップページへ戻る</a>
  </p>
@endsection
