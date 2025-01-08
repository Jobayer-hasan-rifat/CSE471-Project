<x-layouts.club>
    <x-slot name="content">
        <div class="min-h-screen bg-gray-100 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Create New Event</h2>
                        <p class="text-gray-600">Fill in the event details below</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('club.events.store') }}" method="POST" class="space-y-6" id="eventForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <!-- Event Name -->
                                <div>
                                    <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name</label>
                                    <input type="text" name="event_name" id="event_name" value="{{ old('event_name') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('event_name') border-red-500 @enderror">
                                    @error('event_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Event Type -->
                                <div>
                                    <label for="event_type" class="block text-sm font-medium text-gray-700">Event Type</label>
                                    <select name="event_type" id="event_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('event_type') border-red-500 @enderror">
                                        <option value="">Select type...</option>
                                        <option value="workshop" {{ old('event_type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                        <option value="seminar" {{ old('event_type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                        <option value="competition" {{ old('event_type') == 'competition' ? 'selected' : '' }}>Competition</option>
                                        <option value="cultural" {{ old('event_type') == 'cultural' ? 'selected' : '' }}>Cultural</option>
                                        <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('event_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Venue -->
                                <div>
                                    <label for="venue_id" class="block text-sm font-medium text-gray-700">Venue</label>
                                    <select name="venue_id" id="venue_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('venue_id') border-red-500 @enderror">
                                        <option value="">Select venue...</option>
                                        @foreach($venues as $venue)
                                            <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                                {{ $venue->name }} (Capacity: {{ $venue->capacity }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('venue_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Expected Attendance -->
                                <div>
                                    <label for="expected_attendance" class="block text-sm font-medium text-gray-700">Expected Attendance</label>
                                    <input type="number" name="expected_attendance" id="expected_attendance" value="{{ old('expected_attendance') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('expected_attendance') border-red-500 @enderror">
                                    @error('expected_attendance')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Budget -->
                                <div>
                                    <label for="budget" class="block text-sm font-medium text-gray-700">Budget (BDT)</label>
                                    <input type="number" step="0.01" name="budget" id="budget" value="{{ old('budget') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('budget') border-red-500 @enderror">
                                    @error('budget')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Requirements -->
                                <div>
                                    <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements</label>
                                    <textarea name="requirements" id="requirements" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('requirements') border-red-500 @enderror">{{ old('requirements') }}</textarea>
                                    @error('requirements')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Dates -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                                        <input type="datetime-local" 
                                            name="start_date" 
                                            id="start_date" 
                                            value="{{ old('start_date') }}" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('start_date') border-red-500 @enderror">
                                        @error('start_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                                        <input type="datetime-local" 
                                            name="end_date" 
                                            id="end_date" 
                                            value="{{ old('end_date') }}" 
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('end_date') border-red-500 @enderror">
                                        @error('end_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <script>
                                    // Set default values for date inputs
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const now = new Date();
                                        const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
                                        
                                        if (!document.getElementById('start_date').value) {
                                            document.getElementById('start_date').value = localDateTime;
                                        }
                                        
                                        if (!document.getElementById('end_date').value) {
                                            // Set default end date to 1 hour after start date
                                            const endDate = new Date(now.getTime() + 60 * 60000 - now.getTimezoneOffset() * 60000);
                                            document.getElementById('end_date').value = endDate.toISOString().slice(0, 16);
                                        }
                                    });

                                    // Validate end date is after start date
                                    document.getElementById('end_date').addEventListener('change', function() {
                                        const startDate = new Date(document.getElementById('start_date').value);
                                        const endDate = new Date(this.value);
                                        
                                        if (endDate <= startDate) {
                                            alert('End date must be after start date');
                                            this.value = document.getElementById('start_date').value;
                                        }
                                    });
                                </script>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" id="submitBtn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Event
                            </button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('eventForm').addEventListener('submit', function(e) {
                            // Log form data
                            const formData = new FormData(this);
                            console.log('Form Data:');
                            for (let pair of formData.entries()) {
                                console.log(pair[0] + ': ' + pair[1]);
                            }

                            // Disable submit button to prevent double submission
                            document.getElementById('submitBtn').disabled = true;
                        });
                    </script>
                </div>
            </div>
        </div>
    </x-slot>
</x-layouts.club>
