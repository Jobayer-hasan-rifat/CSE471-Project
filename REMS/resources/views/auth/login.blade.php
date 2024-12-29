@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <h1 class="text-3xl font-bold text-indigo-600">REMS</h1>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- User Selection -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Select User')" />
                <select name="email" id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <optgroup label="OCA">
                        <option value="oca@bracu.ac.bd">OCA Admin</option>
                    </optgroup>
                    <optgroup label="Clubs">
                        <option value="bucc@bracu.ac.bd">BUCC</option>
                        <option value="robu@bracu.ac.bd">ROBU</option>
                        <option value="bizbee@bracu.ac.bd">BIZBEE</option>
                        <option value="buedf@bracu.ac.bd">BUEDF</option>
                    </optgroup>
                    <optgroup label="System">
                        <option value="admin@rems.com">System Admin</option>
                    </optgroup>
                </select>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password"
                    type="password"
                    name="password"
                    required autocomplete="current-password"
                    class="block mt-1 w-full" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-3">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>

        <!-- Test Credentials -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600 text-center">Test Password: <span class="font-mono">password</span></p>
        </div>
    </div>
</div>
@endsection
