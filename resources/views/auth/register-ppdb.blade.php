<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Pendaftaran Santri Baru</h2>
        @if(isset($ppdbActive))
            <p class="text-sm text-gray-600 mt-2">
                Gelombang: <span class="font-bold text-emerald-600">{{ $ppdbActive->nama_gelombang }}</span>
            </p>
        @endif
    </div>

    <form method="POST" action="{{ route('ppdb.store') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap (Wali/Santri)')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="jenjang" :value="__('Daftar untuk Jenjang')" />
            <select name="jenjang" id="jenjang" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="" disabled selected>-- Pilih Jenjang Pendidikan --</option>
                <option value="SMP" {{ old('jenjang') == 'SMP' ? 'selected' : '' }}>SMP / MTs</option>
                <option value="SMA" {{ old('jenjang') == 'SMA' ? 'selected' : '' }}>SMA / MA / SMK</option>
                <option value="TAKHOSUS" {{ old('jenjang') == 'TAKHOSUS' ? 'selected' : '' }}>Takhosus (Non-Formal)</option>
            </select>
            <x-input-error :messages="$errors->get('jenjang')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="no_hp" :value="__('Nomor WhatsApp (Aktif)')" />
            <x-text-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp')" required placeholder="0812..." />
            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4 bg-emerald-600 hover:bg-emerald-500">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>