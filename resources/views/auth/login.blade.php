<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <div class="flex"><x-input-label for="user_cd"  :value="__('ログインID')"/><div class="text-[0.7rem] pt-[0.8px] pb-0 mb-0">（nextlinkのログインコードと同様）</div></div>
            <x-text-input id="user_cd" class="block mt-1 w-full" type="text" name="user_cd" :value="old('user_cd')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('user_cd')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
