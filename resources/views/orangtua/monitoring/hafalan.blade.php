<x-app-layout hide-nav>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('orangtua.monitoring.index', $santri->id) }}" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-bold text-lg text-gray-800">Progres Hafalan</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 min-h-screen bg-gray-50">
        
        {{-- Ringkasan Atas --}}
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl p-5 text-white shadow-lg mb-6 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-amber-100 text-xs uppercase tracking-wider font-medium">Total Setoran</p>
                <h3 class="text-3xl font-bold mt-1">{{ $totalSetoran ?? 0 }} <span class="text-sm font-normal text-amber-100">Kali</span></h3>
                <p class="text-xs mt-2 opacity-90">Terus semangati ananda untuk menghafal!</p>
            </div>
            <svg class="absolute -bottom-4 -right-4 w-24 h-24 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.963 7.963 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
        </div>

        {{-- Timeline Hafalan --}}
        <div class="space-y-4 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
            
            @forelse($history as $item)
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    
                    {{-- Icon Dot --}}
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-slate-100 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    
                    {{-- Card Content --}}
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-1">
                            <time class="font-caveat font-bold text-emerald-600 text-sm">
                                {{ $item->created_at->isoFormat('D MMM Y') }}
                            </time>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $item->jenis_setoran == 'ziyadah' ? 'bg-blue-100 text-blue-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ strtoupper($item->jenis_setoran) }}
                            </span>
                        </div>
                        
                        <div class="text-gray-800 font-bold text-base mb-1">
                            {{ $item->materi }} 
                            @if($item->rentang != '-')
                                <span class="text-gray-500 font-normal text-sm block">{{ $item->rentang }}</span>
                            @endif
                        </div>

                        <div class="mt-2 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] text-gray-400">Penyimak:</p>
                                <p class="text-xs text-gray-600">{{ $item->ustadz->nama_lengkap ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="block text-xl font-bold {{ in_array($item->predikat, ['A', 'Mumtaz']) ? 'text-green-600' : (in_array($item->predikat, ['B', 'Jayyid']) ? 'text-blue-600' : 'text-red-600') }}">
                                    {{ $item->predikat }}
                                </span>
                            </div>
                        </div>
                        
                        @if($item->catatan)
                            <div class="mt-2 pt-2 border-t border-dashed border-gray-100 text-xs text-gray-500 italic">
                                "{{ $item->catatan }}"
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-10 ml-8">
                    <p class="text-gray-400 text-sm">Belum ada riwayat hafalan.</p>
                </div>
            @endforelse

        </div>

        <div class="mt-6 ml-8">
            {{ $history->links() }}
        </div>

    </div>
</x-app-layout>