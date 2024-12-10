<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management - REMS</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body class="bg-white text-black">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold">User Management</h1>
        <a href="{{ route('users.create') }}" class="bg-blue-500 text-white p-2 rounded">Create User</a>
        @if (session('success'))
            <div class="bg-green-500 text-white p-2 rounded">{{ session('success') }}</div>
        @endif
        <table class="min-w-full mt-4">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user) }}" class="text-blue-500">Edit</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
