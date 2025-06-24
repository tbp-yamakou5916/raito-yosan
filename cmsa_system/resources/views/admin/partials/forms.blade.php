{{--
https://spatie.be/docs/laravel-html/v3/introduction

使用できるパラメータのリストは下記
https://docs.google.com/spreadsheets/d/1IBqF5Dwpi-vc3OByiOfStO-OM7pzdwum-2W3p-TyJEY/edit?usp=sharing
-------------------------
例）
<input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}">
★type［必須］
'type' => 'text',
☆テキスト系
text / password / tel / url / email / search / textarea
☆セレクト系
selet
※後の二つはselectの変形版
☆カレンダー系
date / month / week / time / dateTime
☆数値
number / range
☆色
color
☆テキスト表示
formText
★name［必須］
'name' => 'created_at',
-------------------------
★ラベル 下記いずれか又は無し
name 利用の場合
'trans' => 'admin.user.user',
直接指定の場合
'label' => 'admin.user.user.name',
テキストで指定の場合
'label_text' => 'ラベル名',
ない場合
no_labelクラスを適用
設定は resources/views/admin/layouts/base.blade.php
--}}
@if(in_array($type, ['checkbox', 'radio', 'file', 'reset', 'submit', 'button', 'image']))
  <div class="form-group">
    「{{ $type }}」には対応していません。
  </div>
@else
  {{-- ラベル用 --}}
  @php($trans = $trans ?? Null)
  @php($label = $label ?? Null)
  @php($label_text = $label_text ?? Null)
  @php($is_label = isset($label) || isset($trans) || isset($label_text))

  {{-- フォーム設定 --}}
  @php($name = $name ?? Null)
  @php($value = $value ?? Null)

  {{-- 前後テキスト --}}
  @php($input_group = false)
  @if(isset($prepend) || isset($append))
    @php($input_group = true)
  @endif
  @php($is_only_form = $is_only_form ?? false)

  {{-- 追加パラメータ --}}
  @php($plus_params = $plus_params ?? [])

  {{-- 更新の場合、通常$datumの値を勝手に設定するが、日付形式は変換が必要 --}}
  @if(in_array($type, ['month', 'week']))
    @if(isset($form_type) && $form_type == 'edit' && isset($datum) && $datum->{$name})
      {{-- ここでエラーが出る可能性あり ※要確認 --}}
      @switch($type)
        @case('month')
          @php($value = $datum->{$name}->format('Y-m'))
          @break
        @case('week')
          @php($value = $datum->{$name}->isoFormat('YYY-Wnn'))
          @break
      @endswitch
    @endif
  @endif

  @php($params = [
      'class' => 'form-control'
  ])
  @foreach($plus_params as $attr => $param)
    @php($params[$attr] = $param)
  @endforeach
  @if(isset($class) && $class)
    @php($params['class'] .= ' ' . $class)
  @endif
  @if(isset($id) && $id)
    @php($params['id'] = $id)
  @endif
  @if(isset($form) && $form)
    @php($params['form'] = $form)
  @endif

  @if(!in_array($type, ['select', 'formText']))
    @if(isset($is_autocomplete) && $is_autocomplete)
      @php($params['autocomplete'] = 'on')
    @elseif(isset($is_autocomplete))
      @php($params['autocomplete'] = 'off')
    @endif
  @endif
  @if(!in_array($type, ['password', 'textarea', 'select', 'formText']))
    @if(isset($list) && $list)
      @php($params['list'] = $list)
    @endif
  @endif

  @if(!in_array($type, ['range', 'color']))
    @if(isset($is_disabled) && $is_disabled)
      @php($params['disabled'] = 'disabled')
    @endif
  @endif
  @if(isset($is_autofocus) && $is_autofocus)
    @php($params['autofocus'] = 'autofocus')
  @endif
  @if(!in_array($type, ['range', 'color', 'select']))
    @if(isset($is_readonly) && $is_readonly)
      @php($params['readonly'] = 'readonly')
    @endif
  @endif
  @if($type == 'range')
    @php($is_required = false)
  @else
    @php($is_required = $is_required ?? false)
    @if($is_required)
      @php($params['required'] = 'required')
    @endif
  @endif
  @if(in_array($type, ['text', 'password', 'tel', 'url', 'email', 'search', 'textarea']))
    @if(isset($maxlength) && $maxlength)
      @php($params['maxlength'] = $maxlength)
    @endif
    @if(isset($minlength) && $minlength)
      @php($params['minlength'] = $minlength)
    @endif
  @endif
  @if(in_array($type, ['text', 'password', 'tel', 'url', 'email', 'search']))
    @if(isset($pattern) && $pattern)
      @php($params['pattern'] = $pattern)
    @endif
  @endif
  @if($type == 'textarea')
    @if(isset($rows) && $rows)
      @php($params['rows'] = $rows)
    @endif
  @endif
  @php($is_multiple = $is_multiple ?? false)
  @if($type == 'email')
    @if($is_multiple)
      @php($params['multiple'] = 'multiple')
    @endif
  @endif
  @if($type == 'select')
    @if($is_multiple)
      @if(isset($size) && $size)
        @php($params['size'] = $size)

      @endif
    @endif
  @endif
  @if($type == 'password')
    {{-- パスワードは更新の場合、必須にしない --}}
    @if($is_required && isset($form_type) && $form_type!='create')
      @isset($params['required'])
        @php($params['required']=Null)
      @endisset
      @php($is_required=false)
    @endif
  @endif
  {{-- 特殊パラメータ --}}
  @if(in_array($type, ['date', 'month', 'week', 'time', 'datetime-local', 'number', 'range']))
    @if(isset($min) && $min)
      @php($params['min'] = $min)
    @endif
    @if(isset($max) && $max)
      @php($params['max'] = $max)
    @endif
    @if(isset($step) && $step)
      @php($params['step'] = $step)
    @endif
  @endif

  {{-- ラベル設定 --}}
  @php($label_class = 'col-form-label')
  @if($trans)
    @php($label_str = __($trans . '.' . $name))
  @elseif($label)
    @php($label_str = __($label))
  @elseif($label_text)
    @php($label_str = $label_text)
  @endisset
  @php($label_id = $id ?? $name)

  {{-- placeholder --}}
  @php($placeholder = $placeholder ?? Null)
  @if(in_array($type, ['text', 'password', 'tel', 'url', 'email', 'search', 'textarea', 'number']))
    @if($placeholder)
      @php($params['placeholder'] = $placeholder)
    @endif
  @endif
  @if($type == 'select')
    @if(isset($is_empty) && $is_empty)
      @php($params['placeholder'] = __('common.empty'))
    @elseif($placeholder)
      @php($params['placeholder'] = $placeholder)
    @endif
  @endif

  {{-- 表示 --}}
  @if(!$is_only_form)
    {{ html()->div()->class(['form-group', $errors->has($name) ? 'has-error' : '', $frame ?? '', $is_label ? Null : 'no_label'])->open() }}
  @endif
  @if($is_label && !$is_only_form)
    {{-- ラベル設定 --}}
    @if($is_required)
      <span class="badge badge-danger required_label">必須</span>
    @endif
    {{ html()->label($label_str, $label_id)->class($label_class) }}
  @endif
  @if($input_group)
    {{-- input_group あり--}}
    {{ html()->div()->class('input-group')->open() }}
    @if(isset($prepend) && $prepend)
      <div class="input-group-prepend">
        <span class="input-group-text">{{ $prepend }}</span>
      </div>
    @endif
  @endif

  @switch($type)
    {{-- テキスト系 --}}
    @case('text')
    @case('tel')
    @case('url')
    @case('email')
    @case('search')
      {{ html()->input($type, $name, $value)->attributes($params) }}
      @break
    @case('textarea')
      {{ html()->textarea($name, $value)->attributes($params) }}
      @break
    @case('password')
      @php($name = $name ?? 'password')
      {{ html()->password($name)->attributes($params) }}
      @break

      {{-- セレクト系 --}}
    @case('select')
      @if($is_multiple)
        {{ html()->multiselect($name, $array, $value)->attributes($params) }}
      @else
        @php($obj = html()->select($name, $array, $value)->attributes($params))
        @isset($params['placeholder'])
          @php($obj = $obj->placeholder($params['placeholder']))
        @endisset
        {{ $obj->render() }}
      @endif
      @break

      {{-- カレンダー系 --}}
    @case('date')
      {{ html()->date($name, $value)->attributes($params) }}
      @break
    @case('time')
      {{ html()->time($name, $value)->attributes($params) }}
      @break
    @case('dateTime')
      {{ html()->datetime($name, $value)->attributes($params) }}
      @break
    @case('month')
    @case('week')
      {{ html()->input($type, $name, $value)->attributes($params) }}
      @break

      {{-- 数値 --}}
    @case('number')
      {{ html()->number($name, $value, 0)->attributes($params) }}
      @break
    @case('range')
      {{ html()->range($name, $value, $params['min'] ?? Null, $params['max'] ?? Null, $params['step'] ?? Null)->attributes($params) }}
      @break

      {{-- 色 --}}
    @case('color')
      @php($params['style']='padding:0')
      {{ html()->input($type, $name, $value)->attributes($params) }}
      @break

      {{-- テキスト表示 --}}
    @case('formText')
      @if($value)
        <span class="form-text">{{ $value }}</span>
      @endif
      @break
  @endswitch

  @if($input_group)
    @if(isset($append) && $append)
      <div class="input-group-append">
        <span class="input-group-text">{!! $append !!}</span>
      </div>
    @endif
    {{ html()->div()->close() }}
  @endif
  {{-- メモ --}}
  @isset($note)
    <span class="text-muted">{!! $note !!}</span>
  @endisset
  {{-- エラー表示 --}}
  @if($errors->has($name))
    <em class="is_valid-feedback">
      {{ $errors->first($name) }}
    </em>
  @endif
  @if(!$is_only_form)
    {{ html()->div()->close() }}
  @endif
@endif

{{-- リセット --}}
@php($array = [])
@php($name = Null)
@php($value = Null)
@php($label_str = Null)
