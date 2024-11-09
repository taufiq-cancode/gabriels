@extends('theme.layout')
@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Users</h2>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Order Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                    <td>{{ Illuminate\Support\Str::title($user->firstname) }} {{ Illuminate\Support\Str::title($user->lastname) }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->orders_count }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-primary btn-sm">View Orders</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $users->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection