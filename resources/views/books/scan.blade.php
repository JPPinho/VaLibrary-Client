@extends('layouts.app')

@section('title', 'Scan ISBN Barcode')

@section('content')
    <div class="scanner-container">
        <h1>Scan a Book's ISBN</h1>
        <p>Position the barcode inside the frame.</p>

        <div id="scanner-region" class="scanner-box"></div>

        <form id="isbn-form" action="{{ route('books.storeFromIsbn') }}" method="POST" class="hidden-form">
            @csrf
            <input type="hidden" name="isbn" id="isbn-input">
        </form>
    </div>
@endsection
