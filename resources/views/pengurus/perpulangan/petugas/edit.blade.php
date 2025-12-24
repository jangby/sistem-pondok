<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24 font-sans">
        {{-- Header --}}
        <div class="bg-emerald-600 pt-8 pb-10 px-6 rounded-b-[35px] shadow-lg">
            <div class="flex justify-between items-center mt-2">
                <h1 class="text-xl font-extrabold text-white">Edit Petugas</h1>
                <a href="{{ route('pengurus.perpulangan.petugas.index') }}" class="text-white opacity-80 hover:opacity-100 transition">
                    Batal
                </a>
            </div>
        </div>

        <div class="px-5 -mt-6">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <form action="{{ route('pengurus.perpulangan.petugas.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-50" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email (Untuk Login)')" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-50" type="email" name="email" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="border-t border-dashed border-gray-200 my-6 pt-4">
                        <h4 class="text-sm font-bold text-gray-700 mb-1">Ganti Password</h4>
                        <p class="text-xs text-gray-500 mb-4">Kosongkan jika tidak ingin mengubah password.</p>

                        <div class="mb-4">
                            <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                            <x-text-input id="password" class="block mt-1 w-full bg-gray-50" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mb-2">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-50" type="password" name="password_confirmation" />
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>