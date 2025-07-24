@extends('layouts.app')

@section('title', 'Add Image Note')

@section('content')
    <div class="container">
        <div class="form-container">
            <h1 class="mb-2">Add Image Note</h1>
            <p class="text-muted h4 mb-4">For the book: <strong>{{ $book->name }}</strong></p>

            <form action="{{ route('notes.storeImage', $book) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="image">Select Image</label>
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" required>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('books.show', $book) }}" class="btn-secondary-action mr-2">Cancel</a>
                    <button type="submit" class="btn-request">Upload Image Note</button>
                </div>
            </form>
        </div>
    </div>
@endsection
