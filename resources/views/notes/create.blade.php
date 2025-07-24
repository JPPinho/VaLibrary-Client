@extends('layouts.app')

@section('title', 'Add a New Note')

@section('content')
    <div class="container">
        <div class="form-container">
            <h1 class="mb-4">Add a New Note</h1>

            <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="book_id">Book</label>
                    <select name="book_id" id="book_id" class="form-control @error('book_id') is-invalid @enderror">
                        <option value="">Select a Book...</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                                {{ $book->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('book_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="body">Note Content</label>
                    <textarea name="body" id="body" rows="8" class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea>
                    @error('body')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="page">Page Number (Optional)</label>
                    <input type="number" name="page" id="page" class="form-control @error('page') is-invalid @enderror" value="{{ old('page') }}">
                    @error('page')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('notes.index') }}" class="btn-secondary-action mr-2">Cancel</a>
                    <button type="submit" class="btn-request">Save Note</button>
                </div>
            </form>
        </div>
    </div>
@endsection
