@extends('layouts.app')

@section('title', 'Share Invitation')

@section('content')
    <div class="qr-code-container">
        <h1>Share this Invitation</h1>
        <p class="qr-subtitle">A new user can scan this code to register.</p>

        <div class="qr-code-box">
            {!! QrCode::size(300)->generate($fullUrl) !!}
        </div>

        <div class="share-actions">
            <a href="{{ $fullUrl }}" class="share-link" id="share-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                <span>Share Link</span>
            </a>
            <input type="text" class="share-url-input" value="{{ $fullUrl }}" readonly>
        </div>
    </div>

    <div id="feedback-popup" class="feedback-popup">
        Link copied to clipboard!
    </div>

    <script>
        document.getElementById('share-link').addEventListener('click', function(event) {
            event.preventDefault();
            const url = document.querySelector('.share-url-input').value;
            const feedbackPopup = document.getElementById('feedback-popup');

            if (navigator.share) {
                navigator.share({
                    title: 'VaLibrary Invitation',
                    text: 'Use this link to join VaLibrary!',
                    url: url,
                }).catch(console.error);
            } else if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    feedbackPopup.classList.add('show');
                    setTimeout(() => {
                        feedbackPopup.classList.remove('show');
                    }, 2000);
                }).catch(err => {
                    alert('Failed to copy link. Please copy it manually.');
                    console.error('Failed to copy: ', err);
                });
            } else {
                alert('Sharing not supported. Please copy the link manually.');
            }
        });

        document.querySelector('.share-url-input').addEventListener('click', function() {
            this.select();
        });
    </script>
@endsection
