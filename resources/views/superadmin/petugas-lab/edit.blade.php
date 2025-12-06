<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Petugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('superadmin.petugas-lab.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$user->name" required />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="$user->email" required />
                    </div>

                    <div class="mb-4 border-t pt-4">
                        <h3 class="text-sm font-bold text-gray-600 mb-2">Ganti Password (Opsional)</h3>
                        <x-input-label for="password" :value="__('Password Baru')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('Ulangi Password Baru')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('superadmin.petugas-lab.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm">Batal</a>
                        <x-primary-button>
                            {{ __('Update Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>