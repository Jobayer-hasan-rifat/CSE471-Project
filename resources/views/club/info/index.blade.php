@extends('components.layouts.club')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-sm p-8">
        <!-- Club Header -->
        <div class="flex items-center space-x-6 mb-8">
            <img src="{{ asset('images/' . strtolower(auth()->user()->getRoleNames()->first()) . '.png') }}" 
                 alt="{{ auth()->user()->getRoleNames()->first() }} Logo" 
                 class="h-24 w-24 object-contain">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $club->full_name }}</h1>
                <p class="text-gray-600">{{ $club->email }}</p>
            </div>
        </div>

        <!-- About Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">About</h2>
            <p class="text-gray-600">{{ $club->description }}</p>
        </div>

        <!-- Club Advisors -->
        <div class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Club Advisors</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($advisors as $advisor)
                    <div class="text-center">
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $advisor['photo']) }}" 
                                 alt="{{ $advisor['name'] }}"
                                 class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                            @if(auth()->user()->hasRole(['bucc', 'buac', 'robu', 'bizbee']))
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openEditModal('{{ $advisor['position'] }}')" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Edit
                                </button>
                            </div>
                            @endif
                        </div>
                        <h3 class="font-semibold text-lg text-gray-900">{{ $advisor['name'] }}</h3>
                        <p class="text-gray-600">{{ $advisor['position'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Executive Committee -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Executive Committee</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($executives as $executive)
                    <div class="text-center">
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $executive['photo']) }}" 
                                 alt="{{ $executive['name'] }}"
                                 class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                            @if(auth()->user()->hasRole(['bucc', 'buac', 'robu', 'bizbee']))
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openEditModal('{{ $executive['position'] }}')" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Edit
                                </button>
                            </div>
                            @endif
                        </div>
                        <h3 class="font-semibold text-lg text-gray-900">{{ $executive['name'] }}</h3>
                        <p class="text-gray-600">{{ $executive['position'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4">Edit Position</h2>
        <form action="{{ route('club.info.update-position') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="position" id="editPosition">
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Name
                </label>
                <input type="text" name="name" id="editName" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="photo">
                    Photo
                </label>
                <input type="file" name="photo" id="editPhoto" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       accept="image/*">
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeEditModal()" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openEditModal(position) {
        document.getElementById('editPosition').value = position;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
</script>
@endpush
@endsection
