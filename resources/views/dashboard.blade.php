@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome Back, {{ $user->name }}!</h1>
    <p>Here is a summary of your library activity.</p>

    <div class="dashboard-grid">
        <div class="dashboard-widget">
            <h3>Books You Own ({{ $user->books->count() }})</h3>
            <ul class="widget-list">
                @forelse ($user->books as $book)
                    <li class="widget-list-item-action">
                        <span>{{ $book->name }}</span>

                        @if ($book->is_available)
                            <span class="status-badge status-available">Available</span>
                        @else
                            <span class="status-badge status-on-loan">On Loan</span>
                        @endif
                    </li>
                @empty
                    <li>You have not added any books to your library yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="dashboard-widget">
            <h3>Active Loans ({{ $user->activeLoans->count() }})</h3>
            <ul class="widget-list">
                @forelse ($user->activeLoans as $loan)
                    <li>
                        {{ $loan->book->name }}
                        <small>(Due: {{ $loan->due_at ? $loan->due_at->toFormattedDateString() : 'N/A' }})</small>
                    </li>
                @empty
                    <li>You have no active loans.</li>
                @endforelse
            </ul>
        </div>

        <div class="dashboard-widget">
            <h3>Books You're Lending ({{ $user->lentBooks->count() }})</h3>
            <ul class="widget-list">
                @forelse ($user->lentBooks as $book)
                    <li class="widget-list-item-action">
                        <div class="item-content">
                            <span>{{ $book->name }}</span>
                            @if($loan = $book->loans->whereNull('returned_at')->first())
                                <small>(To: {{ $loan->borrower->name }})</small>
                            @endif
                        </div>
                        <div class="item-actions">
                            @if($loan = $book->loans->whereNull('returned_at')->first())
                                <form action="{{ route('loans.update', $loan) }}" method="POST" class="d-inline" data-turbo="false">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-return">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="7 10 2 15 7 20"></polyline><path d="M2 15h18"></path></svg>
                                        <span>Returned</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @empty
                    <li>You have not lent any books out right now.</li>
                @endforelse
            </ul>
        </div>


        <div class="dashboard-widget">
            <h3>Your Loan Requests ({{ $user->loanRequests->whereNull('loan_id')->count() }})</h3>
            <ul class="widget-list">
                @forelse ($user->loanRequests->whereNull('loan_id') as $request)
                    <li class="widget-list-item-action">
                        <div class="item-content">
                            <span>{{ $request->book->name }}</span>
                            <small>(Requested: {{ $request->created_at->diffForHumans() }})</small>
                        </div>
                        <div class="item-actions">
                            <form
                                action="{{ route('loan-requests.destroy', $request) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to cancel this request?');"
                            >
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
                    <li>You have no pending loan requests.</li>
                @endforelse
            </ul>
        </div>



        <div class="dashboard-widget">
            <h3>Requests for Your Books ({{ $user->books->flatMap->loanRequests->whereNull('loan_id')->count() }})</h3>
            <ul class="widget-list">
                @forelse ($user->books->flatMap->loanRequests->whereNull('loan_id') as $request)
                    <li class="widget-list-item-action">
                        <div class="item-content">
                            <span>{{ $request->book->name }}</span>
                            <small>(From: {{ $request->borrower->name }})</small>
                        </div>
                        <div class="item-actions">
                            <form action="{{ route('loan-requests.update', $request) }}" method="POST" class="d-inline" data-turbo="false">
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
                    <li>There are no pending requests for your books.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
