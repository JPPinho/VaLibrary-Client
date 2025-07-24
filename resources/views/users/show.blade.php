@extends('layouts.app')

@section('title', 'Profile: ' . $user->name)

@section('content')
    <div class="container">
        <div class="user-profile-header mb-4">
            <div>
                <h1>{{ $user->name }}</h1>
                <p class="text-muted">{{ $user->email }}</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to User List</a>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-widget">
                <h3>Books Owned ({{ $user->books->count() }})</h3>
                <ul class="widget-list">
                    @forelse ($user->books as $book)
                        <li>{{ $book->name }}</li>
                    @empty
                        <li>This user has not added any books to their library.</li>
                    @endforelse
                </ul>
            </div>

            <div class="dashboard-widget">
                <h3>Active Loans ({{ $user->activeLoans->count() }})</h3>
                <ul class="widget-list">
                    @forelse ($user->activeLoans as $loan)
                        <li>
                            {{ $loan->book->name }}
                            @if($loan->due_at)
                                <small>(Due: {{ $loan->due_at->toFormattedDateString() }})</small>
                            @endif
                        </li>
                    @empty
                        <li>This user has no active loans.</li>
                    @endforelse
                </ul>
            </div>

            <div class="dashboard-widget">
                <h3>Books Currently Lent ({{ $user->lentBooks->count() }})</h3>
                <ul class="widget-list">
                    @forelse ($user->lentBooks as $book)
                        <li>
                            {{ $book->name }}
                            @if($loan = $book->loans->whereNull('returned_at')->first())
                                <small>(To: {{ $loan->borrower->name }})</small>
                            @endif
                        </li>
                    @empty
                        <li>This user is not currently lending out any books.</li>
                    @endforelse
                </ul>
            </div>

            <div class="dashboard-widget">
                <h3>Pending Loan Requests Made ({{ $user->loanRequests->whereNull('loan_id')->count() }})</h3>
                <ul class="widget-list">
                    @forelse ($user->loanRequests->whereNull('loan_id') as $request)
                        <li class="widget-list-item-action">
                            <div class="item-content">
                                <span>{{ $request->book->name }}</span>
                                <small>(Requested: {{ $request->created_at->diffForHumans() }})</small>
                            </div>
                            <div class="item-actions">
                                <form action="{{ route('loan-requests.destroy', $request) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?');" data-turbo="false">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-destructive">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        <span>Cancel</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li>This user has no pending loan requests.</li>
                    @endforelse
                </ul>
            </div>

            <div class="dashboard-widget">
                <h3>Pending Requests for User's Books ({{ $user->books->flatMap->loanRequests->whereNull('loan_id')->count() }})</h3>
                <ul class="widget-list">
                    @forelse ($user->books->flatMap->loanRequests->whereNull('loan_id') as $request)
                        <li class="widget-list-item-action">
                            <div class="item-content">
                                <span>{{ $request->book->name }}</span>
                                <small>(From: {{ $request->borrower->name }})</small>
                            </div>
                            <div class="item-actions">
                                <form action="{{ route('loan-requests.update', $request) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-approve">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <span>Approve</span>
                                    </button>
                                </form>
                                <form action="{{ route('loan-requests.destroy', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to reject this request?');" data-turbo="false">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-destructive">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        <span>Reject</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li>There are no pending requests for this user's books.</li>
                    @endforelse
                </ul>
            </div>```
        </div>
    </div>
@endsection
