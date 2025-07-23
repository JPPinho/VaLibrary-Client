@extends('layouts.app')

@section('title', 'Edit Book: ' . $book->name)

@section('content')
    <div class="container">
        <div class="page-header">
            {{-- Link back to the specific book's show page --}}
            <a href="{{ route('books.show', $book) }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Book Details
            </a>
        </div>

        <div class="form-container">
            <h1>Edit Book</h1>

            {{-- The action points to the update route, passing the book --}}
            <form action="{{ route('books.update', $book) }}" method="POST">
                @csrf
                @method('PUT') {{-- Crucial: HTML forms don't support PUT, so Laravel uses this directive --}}

                {{-- Book Title --}}
                <div class="form-group">
                    <label for="name">Book Title</label>
                    {{-- The 'old' helper falls back to the book's current name --}}
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $book->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Language Selection --}}
                <div class="form-group">
                    <label for="language_id">Language</label>
                    <select id="language_id" name="language_id" class="form-control @error('language_id') is-invalid @enderror" required>
                        <option value="">Select a language...</option>
                        @foreach ($languages as $language)
                            {{-- The @selected directive checks old input, then falls back to the book's current language --}}
                            <option value="{{ $language->id }}" @selected(old('language_id', $book->language_id) == $language->id)>
                                {{ $language->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('language_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Author Multi-Select --}}
                <div class="form-group">
                    <label for="authors">Author(s)</label>
                    {{-- The old() helper needs a fallback array of the book's current author IDs --}}
                    @php
                        $selectedAuthors = old('authors', $book->authors->pluck('id')->toArray());
                    @endphp
                    <select id="authors" name="authors[]" class="tom-select @error('authors') is-invalid @enderror" multiple required>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}" @selected(in_array($author->id, $selectedAuthors))>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('authors')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-4">Update Book</button>
            </form>
        </div>
    </div>
@endsection
