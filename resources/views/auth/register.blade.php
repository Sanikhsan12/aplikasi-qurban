<x-guest-layout>
    <h2 class="auth-title" style="color: #1a1a1a; margin-bottom: 0.5rem;">Daftar Akun</h2>
    <p class="auth-subtitle" style="color: #6b7280; margin-bottom: 2rem;">Bergabunglah bersama kami dalam berbagi kebaikan</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
            @error('name')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com" />
            @error('email')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- No HP -->
        <div class="form-group">
            <label for="no_hp" class="form-label">Nomor HP</label>
            <input id="no_hp" class="form-input" type="text" name="no_hp" value="{{ old('no_hp') }}" autocomplete="tel" placeholder="08xxxxxxxxxx" />
            @error('no_hp')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alamat -->
        <div class="form-group">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" class="form-textarea" placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
            @error('alamat')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Hidden Role -->
        <input type="hidden" name="role" value="peserta_kurban" />

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            @error('password')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            @error('password_confirmation')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn-primary">
                Daftar Sekarang
            </button>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <p style="font-size: 0.875rem; color: #6b7280;">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="link-text">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>