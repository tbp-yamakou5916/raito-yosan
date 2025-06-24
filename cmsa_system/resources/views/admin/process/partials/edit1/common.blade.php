{{-- 工程費用項目ID --}}
@php($name = 'items[' . $num . '][process_item_id]')
{{ html()->input('hidden', $name, $item->values['process_item_id']) }}
{{-- 工程ID --}}
@php($name = 'items[' . $num . '][process_id]')
{{ html()->input('hidden', $name, $item->values['process_id']) }}
{{-- 費用項目 --}}
@if($item->values['is_custom'])
  {{-- カスタム費用項目マスターID --}}
  @php($name = 'items[' . $num . '][expense_custom_item_id]')
@else
  {{-- 費用項目マスターID --}}
  @php($name = 'items[' . $num . '][expense_item_id]')
@endif
{{ html()->input('hidden', $name, $item->id) }}
{{-- 費用タイプ --}}
@php($name = 'items[' . $num . '][cost_type]')
{{ html()->input('hidden', $name, $item->cost_type) }}
