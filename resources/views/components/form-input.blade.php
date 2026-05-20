<div class="form-group {{ $class ?? '' }}">
    <label for="{{ $id }}">{{ $label }}</label>

    <input
        type="{{ $type ?? 'text' }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ old($name) }}"
        placeholder="{{ $placeholder ?? '' }}"
        class="@error($name) is-invalid @enderror"
    >

    @error($name)
        <div class="error">
            {{ $message }}
        </div>
    @enderror
</div>
