@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome Back, {{ $user->name }}!</h1>
    <p>Here is a summary of your library activity.</p>

    <div class="dashboard-grid">
        <div class="dashboard-widget">
            <h3>Books You Own ({{ $user->books->count() }})</h3>
            <ul class="widget-list">
                @forelse ($user->books as $book)
                    <li>{{ $book->name }}</li>
                @empty
                    <li>You have not added any books to your library yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="dashboard-widget">
            <h3>Books You've Borrowed ({{ $user->loans->count() }})</h3>
            <ul class="widget-list">
                @forelse ($user->loans as $loan)
                    <li>
                        {{ $loan->book->name }}
                        <small>(Due: {{ $loan->due_at ? $loan->due_at->toFormattedDateString() : 'N/A' }})</small>
                    </li>
                @empty
                    <li>You have no active loans.</li>
                @endforelse
            </ul>
        </div>
        <div class="dashboard-widget">
            <h3>Books You've Lent ({{ $lentBooks->count() }})</h3>
            <ul class="widget-list">
                @forelse ($lentBooks as $book)
                    <li>
                        {{ $book->name }}
                        <small>(Borrowed by: {{ $book->loans->first()->borrower->name }})</small>
                    </li>
                @empty
                    <li>You have not lent any books out right now.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
