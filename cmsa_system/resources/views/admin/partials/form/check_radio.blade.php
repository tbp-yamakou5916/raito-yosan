{{--
checkbox及びradioの設定
iCheckを利用
CSSは「config/adminlte.php」の「Plugins Initialization」で設定
設定項目
[
    // ［必須］対象の配列
    'array' => (array),

    // ［必須］input name
    'name' => (string),

    // ［任意］radio用デフォルトチェック
    // default : Null
    'target' => (integer or string)

    // ［任意］checkbox用デフォルトチェック
    // default : []
    'targets' => (array)

    // ［任意］必須
    // default : false
    'is_required' : (bool)

    // ［任意］ラジオかどうか？
    // default : false
    'is_radio' => (bool)

    // ［任意］inlineかどうか？
    // default : false
    'is_inline' => (bool)

    // ［任意］色の設定
    // default : 'primary'
    'color' => (string)

    // ［任意］ブロックのサイズ
    // default : 3
    'col' => (integer)
]
--}}
{{-- ［任意］radio用デフォルトチェック --}}
@php($target = $target ?? Null)
{{-- ［任意］checkbox用デフォルトチェック --}}
@php($targets = $targets ?? [])
{{-- ［任意］ラジオかどうか --}}
@php($is_required = $is_required ?? false)
{{-- ［任意］ラジオかどうか --}}
@php($is_radio = $is_radio ?? false)
{{-- ［任意］色の設定 --}}
@php($color = $color ?? 'primary')
{{-- ［任意］ブロックのサイズ --}}
@php($col = $col ?? 3)
{{-- ［任意］inlineかどうか --}}
@php($is_inline = $is_inline ?? false)
@php($class = Null)
@if($is_inline)
    @php($class = 'd-inline')
@endif

@php($params = [])
{{-- ［任意］必須 --}}
@php($is_required = $is_required ?? false)
@if($is_required)
    @php($params['required'] = 'required')
@endif

@foreach($array as $value => $label)
    <div class="col-{{ $col }}">
        <div class="form-group clearfix">
            {{ html()->div()->class(['icheck-' . $color, $class])->open() }}
            {{-- $checked --}}
            @php($checked = false)
            {{-- フォーム表示 --}}
            @if($is_radio)
                @if($target && $target == $value)
                    @php($checked = true)
                @endif
                @php($obj = html()->radio($name, $checked, $value))
            @else
                @if(in_array($value, $targets))
                    @php($checked = true)
                @endif
                @php($obj = html()->checkbox($name . '[]', $checked, $value))
            @endif
            @php($form_id = $name . '-' . $value)
            @php($obj = $obj->attributes($params)->id($form_id))
            {{ $obj->render() }}
            {{-- ラベル表示 --}}
            <label for="{{ $form_id }}">
                {!! $label !!}
            </label>
            {{ html()->div()->close() }}
        </div>
    </div>
@endforeach

@if($is_required && !$is_radio)
    @section('js')
        @parent
        <script>
            const submitButton = $('[type=submit]');
            // カスタムエラーメッセージ
            const errorMessage = 'いずれか1つ以上を選択してください。';
            const checkBoxes = $('[name={{ $name }}\\[\\]]');

            checkStatus();
            checkBoxes.on('change', (e) => {
                checkStatus()
            });
            function checkStatus() {
                const isCheckedCount = checkBoxes.filter(':checked');
                isCheckedCount.length > 0
                    ? checkBoxes.prop('required', false)
                    : checkBoxes.prop('required', true);
            }
            // 送信ボタン押下時に処理を開始する
            submitButton.on('click', () => {
                // invalidイベント発火
                checkBoxes.on('invalid', (e) => {
                    // 値が正常か無効かを判定する
                    const isInvalid = e.target.validity.valueMissing;
                    isInvalid
                        // 無効の場合はエラーメッセージを設定する
                        ? e.target.setCustomValidity(errorMessage)
                        // 正常な場合はエラーメッセージを空にする
                        : e.target.setCustomValidity('');
                });
            });
        </script>
    @endsection
@endif

{{-- リセット --}}
@php($array = [])
@php($params = [])
@php($name = Null)
@php($block = Null)
@php($default = Null)
@php($value = Null)
@php($label = Null)

