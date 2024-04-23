@props(['name', 'value'])
<textarea name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" {{ old($name, isset($value) ? $value : '') }} cols="30" rows="4">{{ $value }}</textarea>
@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror