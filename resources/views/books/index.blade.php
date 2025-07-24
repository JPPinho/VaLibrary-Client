@extends('layouts.app')

@section('title', 'All Books')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Book Collection</h1>
            <a href="{{ route('books.index', ['my_collection' => true]) }}" class="btn btn-secondary mr-2">See Your Collection</a>
            <a href="{{ route('books.create') }}" class="btn btn-primary">Add New Book</a>
        </div>
        <div class="filter-bar mb-4">
            <form action="{{ route('books.index') }}" method="GET">
                <div class="filter-grid">
                    <div class="filter-group">
                        <input type="text" name="title" class="form-control" placeholder="Filter by title..." value="{{ request('title') }}">
                    </div>
                    <div class="filter-group">
                        <input type="text" name="owner" class="form-control" placeholder="Filter by owner..." value="{{ request('owner') }}">
                    </div>
                    <div class="filter-group">
                        <select name="author" class="tom-select">
                            <option value="">Filter by author...</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(request('author') == $author->id)>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <select name="language_id" class="tom-select">
                            <option value="">Filter by language...</option>
                            @foreach ($languages as $language)
                                <option value="{{ $language->id }}" @selected(request('language_id') == $language->id)>
                                    {{ $language->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group-checkbox">
                        <input type="checkbox" name="available" id="available" value="1" @checked(request('available'))>
                        <label for="available">Show Available Only</label>
                    </div>
                    <div class="filter-group-buttons">
                        <button type="submit" class="btn-request">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 3v8a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2z"></path><path d="m22 7-2 0"></path><path d="M4 12A8 8 0 0 1 12 4h8"></path><path d="M4 20a8 8 0 0 0 8 4h8"></path><path d="M4 16h4"></path></svg>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('books.index') }}" class="btn-secondary-action">Clear</a>
                    </div>
                </div>
            </form>
        </div>
        @if ($books->isNotEmpty())
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author(s)</th>
                            <th>Language</th>
                            <th>Owner</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>{{ $book->name }}</td>
                                <td>{{ $book->authors->pluck('name')->implode(', ') }}</td>
                                <td>
                                    <span class="badge-language">{{ strtoupper($book->language?->code) }}</span>
                                </td>
                                <td>{{ $book->owner->name ?? 'N/A' }}</td>
                                <td class="actions-cell">
                                    <form action="{{ route('loan-requests.store') }}" method="POST" data-turbo="false">
                                    <div class="action-buttons-wrapper">
                                        <a href="{{ route('books.show', $book) }}" class="action-icon" aria-label="View Book">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>

                                        @if(Auth::id() === $book->owner_id)
                                            <a href="{{ route('books.edit', $book) }}" class="action-icon" aria-label="Edit Book">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                        @endif

                                        @if ($book->is_available && $book->loanRequests->isEmpty() && Auth::id() !== $book->owner_id)
                                                @csrf
                                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                <button type="submit" class="action-icon-button" aria-label="Request Book">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                                </button>
                                        @elseif(!$book->loanRequests->isEmpty())
                                            <span class="action-icon-disabled" aria-label="Request Pending">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                            </span>
                                        @endif
                                    </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">No Books Found</h5>
                    <p class="card-text">Your library is empty. Why not add a new book?</p>
                    <a href="#" class="btn btn-primary">Add the First Book</a>
                </div>
            </div>
        @endif

        <div class="mt-4">
            {{ $books->links() }}
        </div>
    </div>
@endsection
