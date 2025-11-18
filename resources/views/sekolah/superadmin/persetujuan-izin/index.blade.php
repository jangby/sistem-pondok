<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Persetujuan Izin Guru') }}</h2>
</x-slot>
<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 text-gray-900">
    <div class="mb-4">
        <a href="{{ route('sekolah.superadmin.persetujuan-izin.index', ['status' => 'pending']) }}" class="{{ $status == 'pending' ? 'font-bold text-blue-600' : 'text-gray-500' }}">Pending</a> |
        <a href="{{ route('sekolah.superadmin.persetujuan-izin.index', ['status' => 'approved']) }}" class="{{ $status == 'approved' ? 'font-bold text-green-600' : 'text-gray-500' }}">Disetujui</a> |
        <a href="{{ route('sekolah.superadmin.persetujuan-izin.index', ['status' => 'rejected']) }}" class="{{ $status == 'rejected' ? 'font-bold text-red-600' : 'text-gray-500' }}">Ditolak</a>
    </div>
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm font-medium">{{ session('success') }}</div>
    @endif
    
    <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
    <tr>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Guru</th>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
        @if($status == 'pending')
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
        @else
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Catatan Admin</th>
        @endif
    </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
    @forelse ($izins as $izin)
    <tr>
        <td class="px-4 py-3 text-sm">{{ $izin->guru->name }} (<b>{{ $izin->tipe_izin }}</b>)</td>
        <td class="px-4 py-3 text-sm">{{ $izin->tanggal_mulai->format('d/m/Y') }} - {{ $izin->tanggal_selesai->format('d/m/Y') }}</td>
        <td class="px-4 py-3 text-sm">{{ $izin->keterangan_guru }}</td>
        <td class="px-4 py-3 text-sm">
            @if($status == 'pending')
            <form method="POST" action="{{ route('sekolah.superadmin.persetujuan-izin.reject', $izin->id) }}" onsubmit="return confirm('Tolak pengajuan ini?');" class="mb-2">
                @csrf
                <input type="text" name="keterangan_admin" placeholder="Alasan ditolak (wajib)" class="block w-full text-xs border-gray-300 rounded-md shadow-sm" required>
                <button type="submit" class="mt-1 text-xs text-red-600 font-medium">Tolak</button>
            </form>
            <form method="POST" action="{{ route('sekolah.superadmin.persetujuan-izin.approve', $izin->id) }}" onsubmit="return confirm('Setujui pengajuan ini?');">
                @csrf
                <input type="text" name="keterangan_admin" placeholder="Catatan (opsional)" class="block w-full text-xs border-gray-300 rounded-md shadow-sm">
                <button type="submit" class="mt-1 text-xs text-green-600 font-medium">Setujui</button>
            </form>
            @else
            {{ $izin->keterangan_admin ?? '-' }}
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada data.</td></tr>
    @endforelse
    </tbody>
    </table>
    </div>
    <div class="mt-4">{{ $izins->links() }}</div>
</div>
</div>
</div>
</div>
</x-app-layout>