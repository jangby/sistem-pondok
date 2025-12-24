<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24 font-sans">
        <div class="bg-emerald-600 pt-8 pb-10 px-6 rounded-b-[35px] shadow-lg">
            <div class="flex justify-between items-center mt-2">
                <h1 class="text-xl font-extrabold text-white">Tambah Petugas</h1>
                <a href="{{ route('pengurus.perpulangan.petugas.index') }}" class="text-white opacity-80 hover:opacity-100">
                    Batal
                </a>
            </div>
        </div>

        <div class="px-5 -mt-6">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <form action="{{ route('pengurus.perpulangan.petugas.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-50" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email (Untuk Login)')" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-50" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full bg-gray-50" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-50" type="password" name="password_confirmation" required />
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">
                        Simpan Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>