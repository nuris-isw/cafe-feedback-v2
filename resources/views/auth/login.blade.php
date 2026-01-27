<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-serif font-bold text-stone-800">Admin Login</h2>
        <p class="text-sm text-stone-500">Silakan masuk untuk mengelola feedback pengunjung.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-stone-700 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl" 
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                placeholder="admin@anora.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-stone-700 font-semibold" />
            <x-text-input id="password" class="block mt-1 w-full border-stone-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl"
                type="password"
                name="password"
                required autocomplete="current-password" 
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-stone-300 text-amber-600 shadow-sm focus:ring-amber-500" name="remember">
                <span class="ms-2 text-sm text-stone-600">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-stone-500 hover:text-amber-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-3 bg-amber-600 hover:bg-amber-700 active:bg-amber-800 rounded-xl shadow-lg transition-all duration-200">
                {{ __('Masuk ke Dashboard') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>