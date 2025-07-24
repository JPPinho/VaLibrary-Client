@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>User Management</h1>
        </div>

        @forelse ($users as $user)
            @if ($loop->first)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Library Stats</th>
                            <th>Joined On</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @endif

                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-secondary text-capitalize">{{ $user->role }}</span>
                            </td>
                            <td>
                                <div>
                                    <span class="stat-badge" title="Books Owned">Owned: {{ $user->books->count() }}</span>
                                    <span class="stat-badge stat-lending" title="Books currently lent to others">Lending: {{ $user->lentBooks->count() }}</span>
                                    <span class="stat-badge stat-borrowing" title="Books currently borrowed">Borrowing: {{ $user->activeLoans->count() }}</span>
                                </div>
                            </td>
                            <td>{{ $user->created_at->toFormattedDateString() }}</td>
                            <td>
                                <a href="{{ route('users.show', $user) }}" class="action-icon" aria-label="View User">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>
                            </td>
                        </tr>

                        @if ($loop->last)
                        </tbody>
                    </table>
                </div>
            @endif

        @empty
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">No Users Found</h5>
                    <p class="card-text">There are no users in the system.</p>
                </div>
            </div>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
    </div>
@endsection
