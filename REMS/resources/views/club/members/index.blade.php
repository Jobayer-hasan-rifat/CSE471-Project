@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Club Members</h1>
        <div class="flex space-x-4">
            <button onclick="document.getElementById('import-modal').classList.remove('hidden')"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Import Members
            </button>
            <a href="{{ route('club.members.export') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Export Members
            </a>
            <button onclick="document.getElementById('add-member-modal').classList.remove('hidden')"
                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Add Member
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Members Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($members as $member)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $member->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $member->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $member->student_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $member->pivot->role === 'executive' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($member->pivot->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $member->pivot->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('club.members.remove', $member) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Are you sure you want to remove this member?')">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $members->links() }}
    </div>

    <!-- Import Modal -->
    <div id="import-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Import Members</h3>
                <form action="{{ route('club.members.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Excel/CSV File</label>
                        <input type="file" name="file" accept=".xlsx,.csv" required
                            class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-500">
                            File should contain columns: name, email, student_id, role
                        </p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')"
                            class="bg-gray-200 px-4 py-2 rounded text-gray-600 hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Member Modal -->
    <div id="add-member-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Add New Member</h3>
                <form action="{{ route('club.members.add') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Student ID</label>
                            <input type="text" name="student_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="member">Member</option>
                                <option value="executive">Executive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('add-member-modal').classList.add('hidden')"
                            class="bg-gray-200 px-4 py-2 rounded text-gray-600 hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            Add Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
