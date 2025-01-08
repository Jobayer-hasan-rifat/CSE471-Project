<x-layouts.admin>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">System Logs</h2>
                        <form action="{{ route('admin.logs.clear') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                    onclick="return confirm('Are you sure you want to clear all logs?')">
                                Clear Logs
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="bg-gray-50 p-4 rounded-lg font-mono text-sm">
                            @forelse ($logs as $log)
                                <div class="py-1 {{ str_contains(strtolower($log), 'error') ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ $log }}
                                </div>
                            @empty
                                <p class="text-gray-500">No logs available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
