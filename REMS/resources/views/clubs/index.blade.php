@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">All Clubs</h2>
            <div class="relative">
                <input type="text" placeholder="Search clubs..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <p class="text-gray-600 mb-6">Showing {{ $clubs->count() }} of {{ $clubs->count() }} clubs</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($clubs as $club)
            <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                    @if($club->logo)
                    <img src="{{ asset($club->logo) }}" alt="{{ $club->name }}" class="w-full h-full object-cover">
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
                    <a href="{{ route('clubs.show', $club) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        View Details →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
