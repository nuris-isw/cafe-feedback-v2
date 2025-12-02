<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Feedback Kafe Anora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-lg p-8 space-y-6 bg-white rounded-xl shadow-2xl">
        
        {{-- HEADER BERPUSAT: Logo dan Ucapan Selamat Datang --}}
        <header class="text-center space-y-2 mb-6">
            {{-- Placeholder Logo (Ganti dengan tag <img> asli jika sudah ada) --}}
            <div class="w-20 h-20 bg-amber-600/10 rounded-full mx-auto flex items-center justify-center border border-amber-300 shadow-md">
                <span class="text-3xl text-amber-700 font-serif">[Logo]</span>
            </div>
            
            <h1 class="text-3xl font-serif font-extrabold text-stone-800">
                Kafe Anora
            </h1>
            <p class="text-lg text-stone-600 mb-4">
                Selamat Datang! Kami menghargai pendapat Anda.
            </p>
            <hr class="border-amber-100">
        </header>

        {{-- Tampilan Pesan Sukses --}}
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg border border-green-300">
                {{ session('success') }}
            </div>
        @endif
        
        {{-- FORM UTAMA --}}
        <form method="POST" action="{{ route('feedback.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Nama Pengunjung --}}
            <div>
                <label for="visitor_name" class="block text-sm font-medium text-stone-700">Nama Anda</label>
                <input type="text" name="visitor_name" id="visitor_name" required value="{{ old('visitor_name') }}"
                       class="mt-1 block w-full border-stone-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 @error('visitor_name') border-red-500 @enderror"
                       placeholder="Contoh: Risa Adelia">
                @error('visitor_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Email Pengunjung --}}
            <div>
                <label for="visitor_email" class="block text-sm font-medium text-stone-700">Email (Untuk Respon)</label>
                <input type="email" name="visitor_email" id="visitor_email" required value="{{ old('visitor_email') }}"
                       class="mt-1 block w-full border-stone-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 @error('visitor_email') border-red-500 @enderror"
                       placeholder="email@anda.com">
                @error('visitor_email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Rating --}}
            <div>
                <label class="block text-base font-bold text-stone-700 mb-3 text-center">Bagaimana pengalaman Anda?</label>
                
                {{-- Container Bintang Interaktif --}}
                <div class="flex flex-row-reverse justify-center rating-star space-x-0">
                    @for ($i = 5; $i >= 1; $i--)
                        {{-- 1. Input Radio (Peer) --}}
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required
                            class="hidden peer" 
                            {{-- Hapus logika 'checked' awal agar tidak ada rating yang otomatis terpilih --}}
                            @if(old('rating') == $i) checked @endif />
                        
                        {{-- 2. Label Bintang --}}
                        <label for="star{{ $i }}" 
                            {{-- Gunakan 'hover' dan 'peer-checked~' untuk efek menyala ke kiri --}}
                            class="cursor-pointer text-4xl leading-none text-gray-300 transition-colors duration-200 
                                   ease-in-out 
                                   hover:text-amber-500 
                                   peer-hover:text-amber-500 
                                   peer-checked:text-amber-500 
                                   peer-checked~:text-amber-500" 
                            title="{{ $i }} Bintang">
                            ★
                        </label>
                    @endfor
                </div>

                @error('rating')<p class="text-xs text-red-500 mt-3 text-center">{{ $message }}</p>@enderror
            </div>

            {{-- Ulasan/Komentar --}}
            <div>
                <label for="comment" class="block text-sm font-medium text-stone-700">Ulasan & Saran</label>
                <textarea name="comment" id="comment" rows="4" class="mt-1 block w-full border-stone-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 @error('comment') border-red-500 @enderror"
                placeholder="Bagikan pengalaman Anda tentang layanan atau menu... ">{{ old('comment') }}</textarea>
                @error('comment')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Foto Opsional --}}
            <div>
                <label for="photo" class="block text-sm font-medium text-stone-700">Unggah Foto Makanan/Tempat (Opsional)</label>
                <input type="file" name="photo" id="photo" accept="image/*" 
                       class="mt-1 block w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 @error('photo') border-red-500 @enderror">
                @error('photo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tombol Submit --}}
            <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white 
                   bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150">
                Kirim Feedback Anda
            </button>
        </form>
    </div>
</body>
</html>