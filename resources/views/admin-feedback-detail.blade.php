<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Feedback') . ' #' . $feedback->id }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900 grid lg:grid-cols-3 gap-8">
                    
                    {{-- Kolom Kiri: Detail Feedback (2/3 Lebar) --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-4">
                            <h3 class="text-xl font-black text-stone-800">
                                Ulasan dari {{ $feedback->visitor_name }}
                            </h3>
                            <span class="px-4 py-1.5 inline-flex text-xs leading-5 font-black rounded-full uppercase border
                                @if($feedback->status == 'Responded') bg-green-50 text-green-700 border-green-200 
                                @else bg-red-50 text-red-700 border-red-200 @endif">
                                Status: {{ $feedback->status }}
                            </span>
                        </div>

                        {{-- Info Grid: Waktu, Rating, Kategori --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-stone-50 p-5 rounded-xl border border-stone-100">
                            <div>
                                <p class="text-[10px] font-bold text-stone-400 uppercase mb-1">Dimensi/Kategori</p>
                                <span class="px-3 py-1 text-xs font-black rounded-md bg-indigo-600 text-white uppercase shadow-sm">
                                    {{ $feedback->category ?? 'Umum' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-stone-400 uppercase mb-1">Rating Pengunjung</p>
                                <div class="flex items-center text-amber-500 font-bold">
                                    @for ($i = 0; $i < $feedback->rating; $i++) ★ @endfor
                                    <span class="ml-1 text-stone-800">({{ $feedback->rating }}/5)</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-stone-400 uppercase mb-1">Waktu Masuk</p>
                                <p class="text-xs font-bold text-stone-700">{{ $feedback->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        {{-- Komentar --}}
                        <div>
                            <p class="text-sm font-bold text-stone-500 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                Isi Komentar:
                            </p>
                            <div class="text-lg text-stone-800 p-5 bg-white border-2 border-stone-100 rounded-xl italic leading-relaxed shadow-sm">
                                "{{ $feedback->comment ?? 'Tidak ada komentar tertulis.' }}"
                            </div>
                        </div>
                        
                        {{-- Foto Lampiran --}}
                        @if ($feedback->photo_path)
                            <div class="pt-4">
                                <p class="text-sm font-bold text-stone-500 mb-3 uppercase tracking-widest">Bukti Foto:</p>
                                <a href="{{ Storage::url($feedback->photo_path) }}" target="_blank" class="inline-block group">
                                    <div class="relative overflow-hidden rounded-2xl shadow-md border-4 border-white transition-all group-hover:shadow-xl">
                                        <img src="{{ Storage::url($feedback->photo_path) }}" 
                                             alt="Foto Ulasan" 
                                             class="max-w-md h-auto transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white font-bold text-sm bg-black/50 px-3 py-1 rounded-full">Klik untuk Memperbesar</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif

                        {{-- Histori Respon Admin --}}
                        @if ($feedback->admin_response)
                            <div class="pt-6 border-t-2 border-dashed mt-8">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-2 h-6 bg-green-500 rounded-full"></div>
                                    <h4 class="text-lg font-black text-stone-800">Tanggapan Admin Terkirim:</h4>
                                </div>
                                <div class="text-stone-700 p-5 bg-green-50/50 border border-green-100 rounded-xl leading-relaxed">
                                    {{ $feedback->admin_response }}
                                    <p class="text-[10px] text-green-600 mt-3 font-bold uppercase tracking-widest">
                                        Dikirim pada: {{ $feedback->responded_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>


                    {{-- Kolom Kanan: Form Respon (1/3 Lebar) --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-4">
                            <div class="bg-stone-800 p-6 rounded-2xl shadow-xl text-white">
                                <h3 class="text-lg font-black mb-1 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    Berikan Tanggapan
                                </h3>
                                <p class="text-xs text-stone-400 mb-6">Tanggapan akan dikirimkan otomatis ke email pengunjung.</p>
                                
                                <form method="POST" action="{{ route('feedbacks.respond', $feedback) }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div>
                                        <textarea id="response_text" name="response_text" rows="8" required maxlength="500"
                                            placeholder="Tulis pesan Anda di sini..."
                                            class="mt-1 block w-full bg-stone-700 border-stone-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl text-sm placeholder-stone-500 shadow-inner"
                                        >{{ old('response_text', $feedback->admin_response) }}</textarea>
                                        <x-input-error :messages="$errors->get('response_text')" class="mt-2 text-red-400" />
                                    </div>

                                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-stone-900 font-black py-3 rounded-xl transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2 uppercase text-xs">
                                        <span>Kirim Tanggapan</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                </form>
                            </div>

                            {{-- Info Card --}}
                            <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                                <p class="text-[10px] text-indigo-700 font-bold uppercase mb-1">Tips Respon:</p>
                                <p class="text-xs text-indigo-900 leading-relaxed">Gunakan bahasa yang sopan dan apresiatif. Untuk ulasan negatif, fokuslah pada solusi perbaikan fasilitas atau layanan.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>