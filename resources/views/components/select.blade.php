@props(['name', 'value', 'options'])
@php
    $val = old($name, isset($value) ? $value : '');
@endphp
<select name="{{ $name }}" class="form-control @error($name) is-invalid @enderror">
    @foreach ($options as $option)
    {{-- @if ($val == $option['value'])
        <option value="{{ $option['value'] }}" selected>{{ $option['option'] }}</option>
    @endif
        <option value="{{ $option['value'] }}">{{ $option['option'] }}</option> --}}
        <option value="{{ $option['value'] }}" {{ $val == $option['value'] ? 'selected' : '' }}>{{ $option['option'] }}</option>
    @endforeach
</select>
@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
