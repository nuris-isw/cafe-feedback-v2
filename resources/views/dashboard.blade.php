<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Feedback Pengunjung') }}
            </h2>
            
            <div class="flex items-center gap-3">
                {{-- Button Export Excel - Mengirimkan parameter filter aktif ke URL Export --}}
                <a href="{{ route('admin.export-excel', request()->query()) }}" 
                   title="Export Excel" 
                   class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-green-800 active:bg-green-900 transition ease-in-out duration-150 shadow-sm gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>

                {{-- Button Export PDF --}}
                <a href="{{ route('admin.export-pdf', request()->query()) }}" 
                   title="Export PDF" 
                   class="inline-flex items-center px-4 py-2 bg-red-700 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-red-800 active:bg-red-900 transition ease-in-out duration-150 shadow-sm gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h6m-6 4h6"></path>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- STATISTIK CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Feedback</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Rata-rata Rating</p>
                    <p class="text-2xl font-bold text-gray-900">⭐ {{ $stats['average_rating'] }} / 5.0</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Perlu Respon</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['pending'] }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Selesai Direspon</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['responded'] }}</p>
                </div>
            </div>

            {{-- FORM FILTER --}}
            <div class="mb-6 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1 tracking-wider">Filter Rating</label>
                        <select name="rating" class="w-full border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                            <option value="">Semua Rating</option>
                            @for($i=5; $i>=1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>⭐ {{ $i }} Bintang</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1 tracking-wider">Kategori</label>
                        <select name="category" class="w-full border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                            <option value="">Semua Kategori</option>
                            @foreach(['Rasa', 'Pelayanan', 'Suasana', 'Kebersihan', 'Harga', 'Fasilitas'] as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1 tracking-wider">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Responded" {{ request('status') == 'Responded' ? 'selected' : '' }}>Responded</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-amber-600 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-amber-700 transition shadow-sm">
                            Terapkan Filter
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-100 text-gray-600 px-4 py-2 rounded-lg font-bold text-sm text-center hover:bg-gray-200 transition border border-gray-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        
            {{-- TABEL DATA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        Daftar Semua Ulasan Masuk
                    </h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengunjung</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Komentar</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($feedbacks as $feedback)
                                    <tr class="transition-colors @if($feedback->status == 'Pending') bg-amber-50/30 hover:bg-amber-100/50 border-l-4 border-amber-500 @else hover:bg-gray-50 @endif">
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ $loop->iteration + ($feedbacks->perPage() * ($feedbacks->currentPage() - 1)) }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $feedback->visitor_name }}</div>
                                            <div class="text-xs text-indigo-600 font-medium">{{ $feedback->visitor_email }}</div>
                                            <div class="text-[10px] text-gray-400 mt-1 italic">{{ $feedback->created_at->diffForHumans() }}</div>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs overflow-hidden truncate">
                                            {{ \Illuminate\Support\Str::limit($feedback->comment, 60) }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($feedback->photo_path)
                                                <a href="{{ Storage::url($feedback->photo_path) }}" target="_blank" class="inline-block w-10 h-10 rounded-lg overflow-hidden shadow-sm border border-gray-200 hover:scale-110 transition-transform">
                                                    <img src="{{ Storage::url($feedback->photo_path) }}" class="w-full h-full object-cover">
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-300">No Photo</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 text-[10px] font-black rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-tighter">
                                                {{ $feedback->category ?? 'Umum' }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-amber-600">
                                            <div class="flex items-center gap-1">
                                                <span>{{ $feedback->rating }}</span>
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col gap-1.5">
                                                {{-- Status Badge --}}
                                                <div>
                                                    <span class="px-2.5 py-1 text-[10px] leading-none font-black rounded-full uppercase inline-block
                                                        @if($feedback->status == 'Responded') bg-green-100 text-green-800 border border-green-200
                                                        @else bg-red-100 text-red-800 border border-red-200 @endif">
                                                        {{ $feedback->status }}
                                                    </span>
                                                </div>

                                                {{-- Waktu Respon (Hanya muncul jika sudah direspon) --}}
                                                @if ($feedback->responded_at)
                                                    <div class="text-[10px] text-gray-500 font-medium flex items-center gap-1">
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $feedback->responded_at->format('d/m/y H:i') }}
                                                    </div>
                                                @else
                                                    <div class="text-[10px] text-gray-400 italic">Belum ditangani</div>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end items-center">
                                                {{-- Tooltip Wrapper --}}
                                                <div class="relative flex flex-col items-center group">
                                                    <a href="{{ route('feedbacks.show', $feedback) }}" 
                                                    class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all duration-200 shadow-sm border border-indigo-100">
                                                        {{-- Heroicon: Eye --}}
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    
                                                    {{-- Tooltip Text --}}
                                                    <div class="absolute bottom-full mb-2 hidden group-hover:flex flex-col items-center">
                                                        <span class="relative z-10 p-2 text-xs leading-none text-white whitespace-no-wrap bg-gray-800 shadow-lg rounded-md font-bold">
                                                            Lihat & Respon
                                                        </span>
                                                        <div class="w-3 h-3 -mt-2 rotate-45 bg-gray-800"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center gap-2">
                                                <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <p class="text-sm font-medium italic">Tidak ada feedback yang sesuai dengan filter.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $feedbacks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>