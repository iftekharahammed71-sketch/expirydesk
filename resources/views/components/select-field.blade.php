@props(['name', 'label', 'options', 'value' => null, 'placeholder' => null, 'required' => false])
<div>
    <label for="{{ $name }}" class="form-label">{{ $label }} @if($required)<span class="text-red-600">*</span>@endif</label>
    <select id="{{ $name }}" name="{{ $name }}" @required($required) {{ $attributes->merge(['class' => 'form-select']) }}>
        @if($placeholder)<option value="">{{ $placeholder }}</option>@endif
        @foreach($options as $optionValue => $optionLabel)
            @php($optionValue = is_object($optionValue) ? $optionValue->value : $optionValue)
            @php($optionLabel = is_object($optionLabel) && method_exists($optionLabel, 'label') ? $optionLabel->label() : (is_object($optionLabel) ? $optionLabel->name : $optionLabel))
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
</div>
