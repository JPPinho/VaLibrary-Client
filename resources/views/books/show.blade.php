@extends('layouts.app')

@section('title', $book->name)

@section('content')
    <div class="container">
        <div class="page-header">
            <a href="{{ route('books.index') }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to All Books
            </a>

            @if ($book->is_available)
                <span class="status-badge status-available">Available to Loan</span>
            @else
                <span class="status-badge status-on-loan">On Loan</span>
            @endif
        </div>

        <div class="show-grid">
            <div class="show-widget">
                <h3 class="widget-title">Book Information</h3>
                <ul class="info-list">
                    <li><strong>Title:</strong> {{ $book->name }}</li>
                    <li><strong>Author(s):</strong> {{ $book->authors->pluck('name')->implode(', ') }}</li>
                    <li><strong>Language:</strong> <span
                            class="badge-language">{{ strtoupper($book->language->name) }}</span></li>
                    <li><strong>Owner:</strong> {{ $book->owner->name ?? 'N/A' }}</li>
                </ul>
            </div>


            <div class="show-widget">
                <h3 class="widget-title">Actions</h3>

                @if ($book->is_available && !$book->isOwnedBy(Auth::user()) && !$book->hasPendingRequestFrom(Auth::user()))
                    <form action="{{ route('loan-requests.store') }}" method="POST" data-turbo="false">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                        <button type="submit" class="btn-request">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            <span>Request this Book</span>
                        </button>
                    </form>

                @elseif ($book->owner_id === Auth::id())
                    <p class="no-history-text">You are the owner of this book.</p>
                @elseif ($book->hasPendingRequestFrom(Auth::user()))
                    <p class="no-history-text">Please wait for the owner to approve your request.</p>
                @else
                    <p class="no-history-text">This book is currently on loan and cannot be requested.</p>
                @endif
                @if ($canAddNote && !$book->hasPendingRequestFrom(Auth::user()))
                    <a href="{{ route('notes.createImage', $book) }}" class="btn-request" aria-label="Add Image Note">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <span>Add Image</span>
                    </a>
                @endif
            </div>

            <div class="show-widget widget-full-span">
                <h3 class="widget-title">Loan History</h3>
                @forelse ($book->loans as $loan)
                    @if ($loop->first)
                        <table class="history-table">
                            <thead>
                            <tr>
                                <th>Borrower</th>
                                <th>Loaned On</th>
                                <th>Returned On</th>
                            </tr>
                            </thead>
                            <tbody>
                            @endif
                            <tr>
                                <td>{{ $loan->borrower->name ?? 'N/A' }}</td>
                                <td>{{ $loan->created_at->toFormattedDateString() }}</td>
                                <td>
                                    @if ($loan->returned_at)
                                        {{ $loan->returned_at->toFormattedDateString() }}
                                    @else
                                        <span class="status-badge-small status-on-loan">Active</span>
                                    @endif
                                </td>
                            </tr>
                            @if ($loop->last)
                            </tbody>
                        </table>
                    @endif
                @empty
                    <p class="no-history-text">This book has never been loaned out.</p>
                @endforelse
            </div>

            <div class="show-widget widget-full-span">
                <h3 class="widget-title">Notes & Highlights</h3>
                @forelse ($book->notes as $note)
                    <div class="note-item">
                        <blockquote class="note-body">
                            {{ $note->body }}
                        </blockquote>
                        <footer class="note-meta">
                            <span>By: {{ $note->user->name ?? 'Unknown' }}</span>
                            <span>•</span>
                            <time datetime="{{ $note->created_at->toIso8601String() }}">
                                {{ $note->created_at->diffForHumans() }}
                            </time>
                            @if($note->page)
                                <span>•</span>
                                <span>Page {{ $note->page }}</span>
                            @endif
                            @if($note->status)
                                <span class="note-status-badge status-{{ strtolower($note->status->status_code) }}">
                        {{ $note->status->status_code }}
                    </span>
                            @endif
                        </footer>
                    </div>
                @empty
                    <p class="no-history-text">No notes have been added for this book yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
