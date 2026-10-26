@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false])
<div>
    <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-red-600">*</span>@endif</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" @required($required) {{ $attributes->merge(['class' => 'form-input']) }}>
</div>
