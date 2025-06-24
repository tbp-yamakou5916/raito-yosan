@php($is_block = $is_block ?? false)
@php($message = $message ?? Null)
@php($class = $is_block ? 'btn-block btn-sm' : 'btn-sm')
@if(!$message)
    @php($message=__('common.areYouSure'))
@endif
{{ html()->form('DELETE')->route($route, $id)->class('d-inline')->attribute('onsubmit', 'return confirm("' . $message . '")')->open() }}
{{ html()->button(__('common.destroy'), 'submit')->class(['btn', 'btn-danger', $class]) }}
{{ html()->form()->close() }}
