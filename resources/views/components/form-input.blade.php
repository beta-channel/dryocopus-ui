<div class="form-group fill">
    <label class="form-label text-primary f-w-600 mb-2{{ $required ? ' required' : null }}">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}" @class(['form-control', 'is-invalid' => $errors->has($name), $class]) name="{{ $name }}" value="{{ old($name, $defaultValue) }}" {{ $attributes }}>
    @error($name)
        <label class="invalid-feedback">{{ $message }}</label>
    @enderror
</div>
