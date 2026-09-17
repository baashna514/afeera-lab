<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider text-center mb-3">1-Click Quick Demo Login</p>
            <div class="space-y-2">
                <button type="button" onclick="document.getElementById('email').value='owner@medilab.com'; document.getElementById('password').value='password'; document.forms[0].submit();" class="w-full bg-slate-900 text-white text-xs font-semibold py-2.5 px-3 rounded-lg hover:bg-slate-800 transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-crown text-amber-400"></i>
                    <span>Login as SaaS Owner (owner@medilab.com)</span>
                </button>
                <button type="button" onclick="document.getElementById('email').value='admin@citypathology.com'; document.getElementById('password').value='password'; document.forms[0].submit();" class="w-full bg-indigo-600 text-white text-xs font-semibold py-2.5 px-3 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-hospital text-indigo-200"></i>
                    <span>Login as Siyal Hospital Super Admin</span>
                </button>
                <button type="button" onclick="document.getElementById('email').value='admin@apexlabs.com'; document.getElementById('password').value='password'; document.forms[0].submit();" class="w-full bg-cyan-600 text-white text-xs font-semibold py-2.5 px-3 rounded-lg hover:bg-cyan-700 transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-microscope text-cyan-200"></i>
                    <span>Login as Apex Labs Super Admin</span>
                </button>
            </div>
        </div>
    </form>
</x-guest-layout>
