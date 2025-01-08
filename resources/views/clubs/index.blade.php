@extends('components.layouts.admin')

@section('title', 'Clubs')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">All Clubs</h1>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" placeholder="Search clubs..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <a href="{{ route('admin.clubs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Club
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @forelse($clubs as $club)
                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                        @php
                            $clubImage = strtolower($club->name) . '.png';
                            $imagePath = 'images/' . $clubImage;
                            $defaultImage = 'images/default-club.png';
                        @endphp
                        
                        @if(file_exists(public_path($imagePath)))
                            <img src="{{ asset($imagePath) }}" alt="{{ $club->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white text-4xl font-bold">
                                {{ strtoupper(substr($club->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-1">{{ $club->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $club->full_name }}</p>
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <i class="fas fa-envelope mr-2"></i>
                            <a href="mailto:{{ $club->email }}" class="hover:text-indigo-600">{{ $club->email }}</a>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 text-xs rounded-full {{ $club->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $club->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <div class="space-x-2">
                                <a href="{{ route('admin.clubs.edit', $club) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.clubs.show', $club) }}" class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    No clubs found.
                </div>
            @endforelse
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $clubs->links() }}
        </div>
    </div>
</div>
@endsection
