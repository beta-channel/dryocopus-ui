<div class="form-group fill">
    <label class="form-label text-primary f-w-600 mb-0{{ $required ? ' required' : null }}">{{ $label }}</label>
    <textarea class="form-control{{ $errors->has($name) ? ' is-invalid' : null }}" name="{{ $name }}" {{ $attributes }}>{{ old($name, $defaultValue) }}</textarea>
    @error($name)
        <label class="error jquery-validation-error small form-text invalid-feedback">{{ $message }}</label>
    @enderror
</div>
