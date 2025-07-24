@extends('layouts.app')

@section('title', 'My Notes')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>All Notes</h1>
        </div>

        <div class="filter-bar mb-4">
            <form action="{{ route('notes.index') }}" method="GET"">
                <div class="filter-group-search">
                    <div class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                    <input type="text" name="search_term" class="form-control" placeholder="Search by Book Title or Note content..." value="{{ request('search_term') }}">
                </div>
                <div class="filter-group-buttons">
                    <button type="submit" class="btn-request">Filter</button>
                    <a href="{{ route('notes.index') }}" class="btn-secondary-action">Clear</a>
                </div>
            </form>
        </div>

        {{-- The notes table remains the same as before --}}
        @forelse ($notes as $note)
            @if ($loop->first)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="thead-dark">
                        <tr>
                            <th>Book Title</th>
                            <th>Note Excerpt</th>
                            <th>Page</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @endif
                        <tr>
                            <td>{{ $note->book->name }}</td>
                            <td>
                                <span class="note-excerpt">{{ Str::limit($note->body, 100) }}</span>
                            </td>
                            <td>
                                @if($note->page)
                                    {{ $note->page }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $note->created_at->toFormattedDateString() }}</td>
                            <td class="actions-cell">
                                <div class="action-buttons-wrapper">
                                    <a href="#" class="action-icon" aria-label="View Note">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @if ($loop->last)
                        </tbody>
                    </table>
                </div>
            @endif
        @empty
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">No Notes Found</h5>
                    <p class="card-text">No notes match your current filters.</p>
                </div>
            </div>
        @endforelse

        <div class="mt-4 d-flex justify-content-center">
            {{ $notes->links() }}
        </div>

        <a href="{{ route('notes.create') }}" class="fab-add-note" aria-label="Add New Note">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        </a>
    </div>
@endsection
