<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Super Admin Sekolah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h3>
                    <p class="mb-6 text-sm text-gray-600">
                        Anda login sebagai Super Admin Sekolah untuk pondok Anda. Anda dapat mengelola semua unit sekolah formal dari sini.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-blue-700">Total Unit Sekolah</h4>
                            <p class="text-3xl font-bold text-blue-900 mt-2">{{ $jumlahSekolah }}</p>
                        </div>

                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-green-700">Total Akun Admin</h4>
                            <p class="text-3xl font-bold text-green-900 mt-2">{{ $jumlahAdminSekolah }}</p>
                        </div>

                        <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-indigo-700">Total Akun Guru</h4>
                            <p class="text-3xl font-bold text-indigo-900 mt-2">{{ $jumlahGuru }}</p>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>