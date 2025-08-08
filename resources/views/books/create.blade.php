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
            <h1 class="mb-4">Add a New Book</h1>

            <div class="tab-navigation">
                <button class="tab-link active" data-tab="details">Enter Details</button>
                <button class="tab-link" data-tab="images">Upload Images</button>
            </div>

            <div id="details" class="tab-content active">
                <form action="{{ route('books.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Book Title</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="language_id">Language</label>
                        <select id="language_id" name="language_id" class="tom-select-single @error('language_id') is-invalid @enderror">
                            <option value="">Select a language...</option>
                            @foreach ($languages as $language)
                                <option value="{{ $language->id }}" @selected(old('language_id') == $language->id)>{{ $language->name }}</option>
                            @endforeach
                        </select>
                        @error('language_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="authors">Author(s)</label>
                        <select id="authors" name="authors[]" class="tom-select @error('authors') is-invalid @enderror" multiple placeholder="Search for an author...">
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" @selected(in_array($author->id, old('authors', [])))>{{ $author->name }}</option>
                            @endforeach
                        </select>
                        @error('authors')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn-request mt-4">Create Book</button>
                </form>
            </div>

            <div id="images" class="tab-content">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="images">Upload Images</label>
                        <p class="text-muted">You can select multiple images. A new book will be created with each image added as a note.</p>
                        <input type="file" id="images" name="images[]" class="form-control" multiple>
                        @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn-request mt-4">Create from Images</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Your existing tab-switching script remains the same
    </script>
@endsection
