{{-- disk名 config/filesystems.php --}}
@php($disk = $disk ?? Null)
{{-- キャプションの利用 boolean --}}
@php($use_caption = $use_caption ?? Null)
{{-- キャプション --}}
@php($caption = $caption ?? Null)
{{-- 画像存在フラグ --}}
@php($is_exist = (bool) $img_path)
{{-- ファイル name属性名 --}}
@php($attr_file = $attr['file'] ?? 'files')
{{-- 画像 name属性名 --}}
@php($attr_img = $attr['image'] ?? 'images')
{{-- $width 各画像の幅 col-X  --}}
{{-- $height 各画像の高さ px --}}

<div class="img col-{{ $width }} mb-3">
    <input type="file" name="{{ $attr_file }}[{{ $num }}]" class="input_file size" accept="image/*" style="height: {{ $height }}px" >
    <div class="preview_field size" style="height: {{ $height }}px">
        @if($is_exist)
            <img src="{{ Storage::disk($disk)->url($img_path) }}" alt="">
        @endif
    </div>
    <div class="drop_area size {{ $is_exist ? 'd-none' : '' }}" style="height: {{ $height }}px">
        ドラッグ＆ドロップ<br>
        又は<br>
        クリックしてください
    </div>
    <div class="btn_clear badge badge-pill badge-danger {{ $is_exist ? '' : 'd-none' }}">X</div>
    @if($use_caption)
        {{ html()->text($attr_img.'[' . $num . '][caption]', $caption ?? Null)
            ->class(['form-control', 'mt-1', 'caption'])
            ->placeholder('キャプション') }}
    @endif
    {{ html()->hidden($attr_img.'[' . $num . '][is_del]', 0)->class('is_del') }}
    {{ html()->hidden($attr_img.'[' . $num . '][img_path]', $img_path) }}
</div>
