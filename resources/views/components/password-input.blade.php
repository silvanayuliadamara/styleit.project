<div class="form-group">
    <label for="password">Kata Sandi</label>

    <div class="password-wrapper">
        <input
            type="password"
            id="password"
            name="password"
            required
        >

        <button type="button" class="toggle-password" onclick="togglePassword()">
            <i id="eyeIcon" class="bi bi-eye"></i>
        </button>
    </div>

    @error('password')
        <div class="error">{{ $message }}</div>
    @enderror
</div>
