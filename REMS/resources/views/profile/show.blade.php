<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - REMS</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body class="bg-white text-black">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold">Profile</h1>
        @if (session('success'))
            <div class="bg-green-500 text-white p-2 rounded">{{ session('success') }}</div>
        @endif
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update Profile</button>
        </form>
    </div>
