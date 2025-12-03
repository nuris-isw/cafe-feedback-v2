<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Feedback') . ' #' . $feedback->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 grid lg:grid-cols-3 gap-8">
                    
                    {{-- Kolom Kiri: Detail Feedback (2/3 Lebar) --}}
                    <div class="lg:col-span-2 space-y-6">
                        <h3 class="text-xl font-bold border-b pb-2 mb-4 text-indigo-700">
                            Detail Ulasan dari {{ $feedback->visitor_name }}
                        </h3>

                        {{-- Status dan Waktu --}}
                        <div class="flex justify-between items-center pb-2 border-b">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Waktu Dibuat:</p>
                                <p class="text-base text-gray-900">{{ $feedback->created_at->format('d M Y, H:i') }} ({{ $feedback->created_at->diffForHumans() }})</p>
                            </div>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full 
                                @if($feedback->status == 'Responded') bg-green-100 text-green-800 
                                @else bg-red-100 text-red-800 @endif">
                                Status: {{ $feedback->status }}
                            </span>
                        </div>

                        {{-- Rating --}}
                        <div>
                            <p class="text-sm font-medium text-gray-500">Rating:</p>
                            <div class="text-2xl font-bold text-gray-700">
                                <span class="text-yellow-500">
                                    @for ($i = 0; $i < $feedback->rating; $i++)
                                        ★
                                    @endfor
                                </span>
                                ({{ $feedback->rating }}/5)
                            </div>
                        </div>

                        {{-- Kontak Pengunjung --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Pengunjung:</p>
                                <p class="text-base font-semibold text-gray-900">{{ $feedback->visitor_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email Pengunjung:</p>
                                <p class="text-base font-semibold text-indigo-600">{{ $feedback->visitor_email }}</p>
                            </div>
                        </div>

                        {{-- Komentar --}}
                        <div>
                            <p class="text-sm font-medium text-gray-500">Komentar:</p>
                            <p class="text-lg text-gray-800 p-4 bg-gray-50 border rounded-lg">{{ $feedback->comment }}</p>
                        </div>
                        
                        {{-- Foto --}}
                        @if ($feedback->photo_path)
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Foto Lampiran:</p>
                                <a href="{{ Storage::url($feedback->photo_path) }}" target="_blank">
                                    <img src="{{ Storage::url($feedback->photo_path) }}" 
                                         alt="Foto Ulasan" 
                                         class="max-w-xs h-auto rounded-lg shadow-lg border hover:shadow-xl transition duration-300 cursor-pointer">
                                </a>
                            </div>
                        @endif

                        {{-- Respon Admin (Jika Sudah Ada) --}}
                        @if ($feedback->response_text)
                            <div class="pt-6 border-t mt-6">
                                <h4 class="text-lg font-bold text-green-700 mb-2">Respon Admin Sebelumnya:</h4>
                                <p class="text-gray-800 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    {{ $feedback->response_text }}
                                </p>
                            </div>
                        @endif
                    </div>


                    {{-- Kolom Kanan: Form Respon (1/3 Lebar) --}}
                    <div class="lg:col-span-1 bg-gray-50 p-6 rounded-lg shadow-inner">
                        <h3 class="text-xl font-bold border-b pb-2 mb-4 text-gray-800">Form Respon</h3>
                        
                        {{-- Cek apakah feedback sudah direspon --}}
                        @if ($feedback->status == 'Responded')
                            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                                Feedback ini **SUDAH** direspon. Anda dapat mengubah respons di bawah.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('feedbacks.respond', $feedback) }}" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            {{-- Field Respon --}}
                            <div>
                                <x-input-label for="response_text" :value="__('Teks Respon (Maks 500 Karakter)')" />
                                <textarea id="response_text" name="response_text" rows="6" required maxlength="500"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >{{ old('response_text', $feedback->response_text) }}</textarea>
                                <x-input-error :messages="$errors->get('response_text')" class="mt-2" />
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="flex justify-end">
                                <x-primary-button>
                                    {{ __('Kirim/Update Respon') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>