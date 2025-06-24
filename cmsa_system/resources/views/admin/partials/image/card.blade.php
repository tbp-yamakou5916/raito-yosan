{{-- タイトル str --}}
@php($title = $title ?? Null)
{{-- .card 追加クラス --}}
@php($card_class = $card_class ?? Null)
{{-- 各画像の幅 col-X  --}}
@php($width = $width ?? 12)
{{-- 各画像の高さ px --}}
@php($height = $height ?? 240)
{{-- .card ID --}}
@php($card_id = $card_id ?? Null)
{{-- disk名 config/filesystems.php --}}
@php($disk = $disk ?? Null)
{{-- 画像枚数 --}}
@php($max = $max ?? 1)
{{-- 画像データ取得 --}}
@php($images = $images ?? Null)
{{-- キャプションの利用 boolean --}}
@php($use_caption = $use_caption ?? Null)
{{-- 開始番号 --}}
@php($start_num = $start_num ?? 0)

<div class="card {{ $card_class }}" id="{{ $card_id }}">
    @if($title)
        <div class="card-header bg-secondary">
        <span class="card-title">
            {{ $title }}
        </span>
        </div>
    @endif
    <div class="card-body pb-2">
        <div class="row">
            {{ html()->hidden('disk', $disk) }}
            @for($i=0; $i<$max; $i++)
                @php($img_path = $images[$i][0] ?? Null)
                @php($caption = $images[$i][1] ?? Null)
                @include('admin.partials.image.block', [
                    'disk' => $disk,
                    'num' => $start_num + $i + 1,
                    'width' => $width,
                    'height' => $height,
                    'use_caption' => $use_caption,
                    'img_path' => $img_path,
                    'caption' => $caption,
                ])
            @endfor
        </div>
    </div>
</div>

@section('css')
    @parent
    @include('admin.partials.image.css')
@endsection
@section('js')
    @parent
    @include('admin.partials.image.js')
@endsection

