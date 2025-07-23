@extends('layouts.app')

@section('title', 'Add New Book')

@section('content')
    <div class="container">
        <div class="page-header">
            <a href="{{ route('books.index') }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to All Books
            </a>
        </div>

        <div class="form-container">
            <h1>Add a New Book</h1>
            <form action="{{ route('books.store') }}" method="POST">
                @csrf

                {{-- Book Title --}}
                <div class="form-group">
                    <label for="name">Book Title</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
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
                            <option value="{{ $language->id }}" @selected(old('language_id') == $language->id)>
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
                    <select id="authors" name="authors[]" class="tom-select @error('authors') is-invalid @enderror" multiple required>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}" @selected(in_array($author->id, old('authors', [])))>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('authors')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-4">Create Book</button>
            </form>
        </div>
    </div>
@endsection
