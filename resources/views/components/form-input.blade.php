<div class="form-group {{ $class ?? '' }}">
    <label for="{{ $id }}">{{ $label }}</label>

    <input
        type="{{ $type ?? 'text' }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ old($name) }}"
        placeholder="{{ $placeholder ?? '' }}"
        {{ !empty($required) ? 'required' : '' }}
    >

    @error($name)
        <div class="error">{{ $message }}</div>
    @enderror
</div>
