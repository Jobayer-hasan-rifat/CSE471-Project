@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create a Proposal</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('event-planner.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required>
                @error('title')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" required></textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="date">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control" required>
                @error('date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="checkbox" name="termsAgreed" required>
                <label for="termsAgreed">I agree to the terms</label>
                @error('termsAgreed')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Submit Proposal</button>
        </form>

        < ```blade <h2>Pending Events</h2>
            <ul>
                @foreach ($pendingRequests as $event)
                    <li>{{ $event['title'] }} - {{ $event['description'] }}</li>
                @endforeach
            </ul>

            <h2>Recent Proposals</h2>
            <ul>
                @foreach ($respondedRequests as $event)
                    <li>{{ $event['title'] }} - {{ $event['response'] }}</li>
                @endforeach
            </ul>
    </div>
@endsection
