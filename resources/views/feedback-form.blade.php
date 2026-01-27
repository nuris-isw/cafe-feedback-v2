<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Feedback Kafe Anora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 flex items-center justify-center min-h-screen p-4 font-sans">
    <div class="w-full max-w-lg p-8 space-y-6 bg-white rounded-xl shadow-2xl">
        
        {{-- HEADER BERPUSAT --}}
        <header class="text-center space-y-2 mb-6">
            <div class="w-20 h-20 bg-amber-600/10 rounded-full mx-auto flex items-center justify-center border border-amber-300 shadow-md">
                <span class="text-3xl text-amber-700 font-serif font-bold">A</span>
            </div>
            
            <h1 class="text-3xl font-serif font-extrabold text-stone-800 tracking-tight">
                Kafe Anora
            </h1>
            <p class="text-stone-600">
                Selamat Datang! Kami menghargai pendapat Anda.
            </p>
            <hr class="border-amber-100 w-24 mx-auto border-t-2">
        </header>

        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 bg-green-50 rounded-lg border border-green-200 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label for="visitor_name" class="block text-sm font-semibold text-stone-700 mb-1">Nama Anda</label>
                <input type="text" name="visitor_name" id="visitor_name" required value="{{ old('visitor_name') }}"
                       class="w-full border-stone-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 @error('visitor_name') border-red-500 @enderror"
                       placeholder="Contoh: Risa Adelia">
                @error('visitor_name')<p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="visitor_email" class="block text-sm font-semibold text-stone-700 mb-1">Email (Untuk Respon)</label>
                <input type="email" name="visitor_email" id="visitor_email" required value="{{ old('visitor_email') }}"
                       class="w-full border-stone-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 @error('visitor_email') border-red-500 @enderror"
                       placeholder="email@anda.com">
                @error('visitor_email')<p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- Rating --}}
            <div class="bg-stone-50 p-4 rounded-xl border border-stone-200">
                <label class="block text-sm font-bold text-stone-700 mb-3 text-center">Bagaimana pengalaman Anda?</label>
                <div class="flex flex-row-reverse justify-center gap-1">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required class="hidden peer" @if(old('rating') == $i) checked @endif />
                        <label for="star{{ $i }}" class="cursor-pointer text-4xl text-stone-300 transition-all hover:text-amber-400 peer-hover:text-amber-400 peer-checked:text-amber-500 peer-checked~:text-amber-500">★</label>
                    @endfor
                </div>
                @error('rating')<p class="text-xs text-red-500 mt-3 text-center font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- Komentar --}}
            <div>
                <label for="comment" class="block text-sm font-semibold text-stone-700 mb-1">Ulasan & Saran</label>
                <textarea name="comment" id="comment" rows="3" class="w-full border-stone-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 @error('comment') border-red-500 @enderror"
                placeholder="Bagikan pengalaman Anda tentang layanan atau menu kami... ">{{ old('comment') }}</textarea>
                @error('comment')<p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>@enderror
            </div>

            {{-- OPTIMASI UI: Bagian Kamera & Foto --}}
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-stone-700">Lampirkan Foto (Maks. 2MB)</label>
                
                {{-- Pilihan Metode Upload --}}
                <div class="grid grid-cols-2 gap-3" id="photo-options">
                    {{-- Opsi Galeri --}}
                    <label for="photo" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-stone-300 rounded-xl hover:bg-amber-50 hover:border-amber-400 cursor-pointer transition-all">
                        <svg class="w-8 h-8 text-stone-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-xs font-bold text-stone-600 uppercase tracking-tighter">Pilih File</span>
                    </label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="hidden">

                    {{-- Opsi Kamera --}}
                    <button type="button" id="start-camera" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-stone-300 rounded-xl hover:bg-amber-50 hover:border-amber-400 transition-all">
                        <svg class="w-8 h-8 text-stone-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-xs font-bold text-stone-600 uppercase tracking-tighter">Ambil Foto</span>
                    </button>
                </div>

                {{-- Area Kerja Kamera (Tersembunyi) --}}
                <div id="camera-container" class="hidden bg-stone-900 rounded-xl overflow-hidden shadow-lg relative">
                    <video id="video" class="w-full h-64 object-cover" autoplay playsinline></video>
                    <div class="absolute bottom-4 inset-x-0 flex justify-center gap-3">
                        <button type="button" id="click-photo" class="bg-white p-3 rounded-full shadow-lg hover:scale-110 active:scale-95 transition">
                            <div class="w-8 h-8 bg-amber-600 rounded-full border-2 border-white"></div>
                        </button>
                        <button type="button" id="close-camera" class="bg-red-600 text-white p-2 rounded-full shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <canvas id="canvas" class="hidden"></canvas>
                
                {{-- Preview Hasil --}}
                <div id="photo-preview-container" class="hidden relative group border rounded-xl overflow-hidden shadow-md">
                    <img id="photo-preview" src="" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <button type="button" id="remove-photo" class="bg-white text-red-600 px-3 py-1 rounded-full text-xs font-bold shadow-lg">Hapus & Ulangi</button>
                    </div>
                    <div class="bg-stone-800 text-white text-[10px] uppercase font-bold text-center py-1">Siap untuk dikirim</div>
                </div>
                
                {{-- Label Nama File (Muncul jika pilih via Galeri) --}}
                <div id="file-name" class="hidden text-xs text-amber-700 font-semibold bg-amber-50 p-2 rounded border border-amber-200 truncate"></div>
            </div>

            <button type="submit" class="w-full py-3 px-4 rounded-xl shadow-lg text-white bg-amber-600 hover:bg-amber-700 font-bold transition-all hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                <span>Kirim Feedback</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>
    </div>
</body>
</html>