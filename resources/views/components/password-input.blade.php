<div class="form-group {{ $class ?? '' }}">
    <label for="{{ $id ?? 'password' }}">{{ $label ?? 'Kata Sandi' }}</label>

    <div class="password-wrapper">
        <input
            type="password"
            id="{{ $id ?? 'password' }}"
            name="{{ $name ?? 'password' }}"
            required
        >

        <button type="button" class="toggle-password" onclick="togglePassword('{{ $id ?? 'password' }}', '{{ $iconId ?? 'eyeIcon' }}')">
            <i id="{{ $iconId ?? 'eyeIcon' }}" class="bi bi-eye"></i>
        </button>
    </div>

    @error($name ?? 'password')
        <div class="error">{{ $message }}</div>
    @enderror
</div>
