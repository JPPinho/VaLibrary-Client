@extends('layouts.app')

@section('title', 'Loan History')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Loan History</h1>
        </div>

        @forelse ($loans as $loan)
            @if ($loop->first)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th>Book Title</th>
                            <th>Borrower</th>
                            <th>Lender</th>
                            @if (Auth::user()->role !== 'admin')
                                <th>Your Role</th>
                            @endif
                            <th>Status</th>
                            <th>Loaned On</th>
                        </tr>
                        </thead>
                        <tbody>
                        @endif
                        <tr>
                            <td>{{ $loan->id }}{{ $loan->book->name }}</td>
                            <td>{{ $loan->borrower->name }}</td>
                            <td>{{ $loan->book->owner->name }}</td>

                            @if (Auth::user()->role !== 'admin')
                                <td>
                                    @if ($loan->borrower_id === Auth::id())
                                        <span class="role-badge role-borrowing">Borrowing</span>
                                    @else
                                        <span class="role-badge role-lending">Lending</span>
                                    @endif
                                </td>
                            @endif

                            <td>
                                @if ($loan->returned_at)
                                    <span class="status-badge status-returned">Returned</span>
                                @elseif ($loan->due_at && $loan->due_at->isPast())
                                    <span class="status-badge status-overdue">Overdue</span>
                                @else
                                    <span class="status-badge status-active">Active</span>
                                @endif
                            </td>
                            <td>{{ $loan->created_at->toFormattedDateString() }}</td>
                        </tr>

                        @if ($loop->last)
                        </tbody>
                    </table>
                </div>
            @endif

        @empty
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">No Loan History Found</h5>
                    <p class="card-text">There are no loans matching your account.</p>
                </div>
            </div>
            @endforelse

            <div class="mt-4">
                {{ $loans->links() }}
            </div>
    </div>
@endsection
