<x-layouts.club>
    <x-slot name="content">
        <div class="container mx-auto px-4 py-8">
            <!-- Club Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold">{{ $club->name }}</h1>
                    <button onclick="openPositionModal()" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Add Position
                    </button>
                </div>
                <p class="text-gray-600">{{ $club->description }}</p>
            </div>

            <!-- Club Positions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($club->positions as $position)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative">
                            @if($position->image_path)
                                <img src="{{ asset('storage/' . $position->image_path) }}" 
                                     alt="{{ $position->member_name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                            <button onclick="editPosition({{ $position->id }})" 
                                    class="absolute top-2 right-2 bg-white rounded-full p-2 shadow hover:bg-gray-100">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg">{{ $position->position_name }}</h3>
                            <p class="text-gray-600">{{ $position->member_name }}</p>
                            @if($position->email)
                                <p class="text-sm text-gray-500">{{ $position->email }}</p>
                            @endif
                            @if($position->phone)
                                <p class="text-sm text-gray-500">{{ $position->phone }}</p>
                            @endif
                            @if($position->description)
                                <p class="mt-2 text-gray-600 text-sm">{{ $position->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Add/Edit Position Modal -->
        <div id="positionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 id="modalTitle" class="text-xl font-semibold">Add Position</h2>
                            <button type="button" onclick="closePositionModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <form id="positionForm" onsubmit="submitPositionForm(event)">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST">
                            <input type="hidden" name="position_id" id="positionId">

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Position Name</label>
                                    <input type="text" name="position_name" id="positionName" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Member Name</label>
                                    <input type="text" name="member_name" id="memberName" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="text" name="phone" id="phone"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image</label>
                                    <input type="file" name="image" id="image" accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" onclick="closePositionModal()"
                                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Alert -->
        <div id="alert" class="fixed top-4 right-4 max-w-sm w-full hidden z-50">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div id="alertContent" class="p-4"></div>
            </div>
        </div>
    </x-slot>

    @push('scripts')
    <script>
        function openPositionModal() {
            document.getElementById('modalTitle').textContent = 'Add Position';
            document.getElementById('positionForm').reset();
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('positionId').value = '';
            document.getElementById('positionModal').classList.remove('hidden');
        }

        function closePositionModal() {
            document.getElementById('positionModal').classList.add('hidden');
            document.getElementById('positionForm').reset();
        }

        function showAlert(message, type = 'success') {
            const alert = document.getElementById('alert');
            const alertContent = document.getElementById('alertContent');
            
            alertContent.textContent = message;
            alertContent.className = `p-4 ${type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
            
            alert.classList.remove('hidden');
            setTimeout(() => {
                alert.classList.add('hidden');
            }, 3000);
        }

        function submitPositionForm(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const method = document.getElementById('formMethod').value;
            const positionId = document.getElementById('positionId').value;
            
            let url = '{{ route("club.positions.store") }}';
            if (method === 'PUT') {
                url = `/club/positions/${positionId}`;
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message || 'Position saved successfully');
                    closePositionModal();
                    // Reload the page to show updated positions
                    window.location.reload();
                } else {
                    showAlert(data.message || 'Error saving position', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while saving the position', 'error');
            });
        }

        function editPosition(id) {
            document.getElementById('modalTitle').textContent = 'Edit Position';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('positionId').value = id;
            
            fetch(`/club/positions/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('positionName').value = data.position_name;
                    document.getElementById('memberName').value = data.member_name;
                    document.getElementById('email').value = data.email || '';
                    document.getElementById('phone').value = data.phone || '';
                    document.getElementById('description').value = data.description || '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error loading position data', 'error');
                });

            document.getElementById('positionModal').classList.remove('hidden');
        }
    </script>
    @endpush
</x-layouts.club>
