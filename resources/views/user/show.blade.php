@extends('layouts.app')

@section('title', 'Profile: ' . $user->name)

@section('content')
    <div class="container">
        <div class="user-profile-header mb-4">
            <div>
                <h1>{{ $user->name }}</h1>
                <p class="text-muted">{{ $user->email }}</p>
            </div>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Back to User List</a>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-widget">
                <h3>Books Owned ({{ $user->books->count() }})</h3>
                <ul class="widget-list">
                    @forelse ($user->books as $book)
                        <li>{{ $book->name }}</li>
                    @empty
                        <li>This user has not added any books to their library.</li>
                    @endforelse
                </ul>
            </div>

            <div class="dashboard-widget">
                <h3>Books Currently Borrowed ({{ $user->activeLoans->count() }})</h3>
                <ul class="widget-list">
                    @forelse ($user->activeLoans as $loan)
                        <li>
                            {{ $loan->book->name }}
                            @if($loan->due_at)
                                <small>(Due: {{ $loan->due_at->toFormattedDateString() }})</small>
                            @endif
                        </li>
                    @empty
                        <li>This user has no active loans.</li>
                    @endforelse
                </ul>
            </div>

            <div class="dashboard-widget">
                <h3>Books Currently Lent Out ({{ $user->lentBooks->count() }})</h3>
                <ul class="widget-list">
                    @forelse ($user->lentBooks as $book)
                        <li>
                            {{ $book->name }}
                            @if($loan = $book->loans->whereNull('returned_at')->first())
                                <small>(To: {{ $loan->borrower->name }})</small>
                            @endif
                        </li>
                    @empty
                        <li>This user is not currently lending out any books.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
