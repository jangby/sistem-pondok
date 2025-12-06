<x-app-layout hide-nav>
    <div class="bg-white p-4 shadow-sm flex items-center sticky top-0 z-30">
        <a href="{{ route('petugas-lab.dashboard') }}" class="mr-4 text-gray-600 hover:text-blue-600">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Lapor Masalah</h1>
    </div>

    <div class="p-6">
        <form action="#" class="space-y-4">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Pilih Komputer</label>
                <select class="w-full mt-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    <option>PC-LAB-01</option>
                    <option>PC-LAB-02</option>
                    <option>Jaringan / Wifi</option>
                    <option>Lainnya</option>
                </select>
            </div>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Deskripsi Masalah</label>
                <textarea rows="4" class="w-full mt-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Mouse tidak berfungsi..."></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Foto Bukti (Opsional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl bg-gray-50">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-camera text-gray-400 text-3xl"></i>
                        <div class="text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Upload file</span>
                                <input type="file" class="sr-only">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl shadow hover:bg-indigo-700 mt-4">
                Kirim Laporan
            </button>
        </form>
    </div>
</x-app-layout>