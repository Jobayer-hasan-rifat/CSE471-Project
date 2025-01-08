@extends('layouts.guest')

@section('content')
    <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-4 text-center">
            <img src="{{ asset('images/brac.png') }}" alt="BRAC Logo" class="h-12 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-700">Welcome to REMS</h2>
            <p class="text-gray-600">Reservation & Event Management System</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-4">
                <div class="font-medium text-red-600">
                    {{ __('Whoops! Something went wrong.') }}
                </div>

                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Select User</label>
                <select id="role" name="role" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="">Select a user...</option>
                    <option value="oca" {{ old('role') == 'oca' ? 'selected' : '' }}>OCA</option>
                    <option value="bucc" {{ old('role') == 'bucc' ? 'selected' : '' }}>BUCC</option>
                    <option value="buac" {{ old('role') == 'buac' ? 'selected' : '' }}>BUAC</option>
                    <option value="robu" {{ old('role') == 'robu' ? 'selected' : '' }}>ROBU</option>
                    <option value="bizbee" {{ old('role') == 'bizbee' ? 'selected' : '' }}>BIZBEE</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign in
                </button>
            </div>
        </form>

        <!-- Back to Home -->
        <div class="mt-4 text-center">
            <a href="{{ route('welcome') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                &larr; Back to Home
            </a>
        </div>
    </div>
@endsection
