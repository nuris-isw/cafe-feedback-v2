<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Feedback Pengunjung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                {{-- Total Feedback --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Feedback</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>

                {{-- Rata-rata Rating --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Rata-rata Rating</p>
                    <p class="text-2xl font-bold text-gray-900">⭐ {{ $stats['average_rating'] }} / 5.0</p>
                </div>

                {{-- Masukan Pending --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Perlu Respon</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['pending'] }}</p>
                </div>

                {{-- Sudah Direspon --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Selesai Direspon</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['responded'] }}</p>
                </div>
            </div>
        
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4 border-b pb-2">Daftar Semua Ulasan Masuk</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengunjung & Waktu</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Komentar</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Respon</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Respon</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($feedbacks as $feedback)
                                    <tr class="@if($feedback->status == 'Pending') bg-red-50 hover:bg-red-100 @else hover:bg-gray-50 @endif">
                                        
                                        {{-- 1. NOMOR (Baru) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                            {{ $loop->iteration + ($feedbacks->perPage() * ($feedbacks->currentPage() - 1)) }}
                                        </td>
                                        
                                        {{-- 2. PENGUNJUNG & WAKTU --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $feedback->visitor_name }}</div>
                                            <div class="text-xs text-indigo-600">{{ $feedback->visitor_email }}</div>
                                            <div class="text-xs text-gray-400 mt-1">{{ $feedback->created_at->diffForHumans() }}</div>
                                        </td>

                                        {{-- 3. KOMENTAR --}}
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs overflow-hidden truncate">
                                            {{ \Illuminate\Support\Str::limit($feedback->comment, 80) }}
                                        </td>
                                        
                                        {{-- 4. FOTO (Thumbnail Interaktif) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($feedback->photo_path)
                                                {{-- Link untuk membuka foto besar di tab baru --}}
                                                <a href="{{ Storage::url($feedback->photo_path) }}" target="_blank" class="block mx-auto w-10 h-10 rounded-md overflow-hidden shadow-md border hover:shadow-lg transition duration-150">
                                                    {{-- Thumbnail Foto --}}
                                                    <img src="{{ Storage::url($feedback->photo_path) }}" alt="Foto Ulasan" class="w-full h-full object-cover">
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>

                                        {{-- 5. RATING --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                                            <span class="text-yellow-500">
                                                @for ($i = 0; $i < $feedback->rating; $i++)
                                                    ★
                                                @endfor
                                            </span>
                                            ({{ $feedback->rating }}/5)
                                        </td>
                                        
                                        {{-- 6. STATUS RESPON --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full 
                                                @if($feedback->status == 'Responded') bg-green-100 text-green-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $feedback->status }}
                                            </span>
                                        </td>

                                        {{-- 7. WAKTU RESPON --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if ($feedback->responded_at)
                                                {{ $feedback->responded_at->format('d M Y, H:i') }}
                                            @else
                                                <span class="text-xs text-gray-400">Belum direspon</span>
                                            @endif
                                        </td>
                                        
                                        {{-- 8. AKSI --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('feedbacks.show', $feedback) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Lihat Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            🎉 Belum ada feedback baru!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $feedbacks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>