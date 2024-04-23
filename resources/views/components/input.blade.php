@props(['type', 'name', 'value'])
<input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, isset($value) ? $value : '') }}"
    class="form-control @error($name) is-invalid @enderror" />
@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
