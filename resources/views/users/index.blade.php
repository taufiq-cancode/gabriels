@extends('theme.layout')
@section('content')
<div class="container mt-5">

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('orders') }}" class="btn btn-secondary me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="mb-0">Users</h2>
    </div>    

    <div class="table-responsive">
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
            <tbody id="users-tbody">
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
    </div>

    <div id="pagination-container" class="d-flex justify-content-center mt-3">
        {{ $users->links('vendor.pagination.bootstrap-4') }}
    </div>

    <!-- Loading spinner -->
    <div id="loading-spinner" style="display: none; text-align: center; margin-top: 20px;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('pagination-container').addEventListener('click', function(event) {
        event.preventDefault();
        if (event.target.tagName === 'A') {
            const url = event.target.href;
            fetchUsers(url);
        }
    });
});

function fetchUsers(url) {
    const spinner = document.getElementById('loading-spinner');
    const usersTbody = document.getElementById('users-tbody');
    const paginationContainer = document.getElementById('pagination-container');

    // Show spinner and clear table temporarily
    spinner.style.display = 'block';
    usersTbody.innerHTML = '';
    paginationContainer.innerHTML = '';

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Calculate the starting serial number based on the page
        const currentPage = new URL(url).searchParams.get('page') || 1;
        const startSerial = (currentPage - 1) * 5; // Adjust for items per page

        let usersHtml = '';
        data.users.forEach((user, index) => {
            usersHtml += `
                <tr>
                    <td>${startSerial + index + 1}</td>
                    <td>${user.firstname} ${user.lastname}</td>
                    <td>${user.email}</td>
                    <td>${user.orders_count}</td>
                    <td>
                        <a href="/admin/users/${user.id}" class="btn btn-primary btn-sm">View Orders</a>
                    </td>
                </tr>
            `;
        });
        usersTbody.innerHTML = usersHtml;

        // Update pagination links and hide spinner
        paginationContainer.innerHTML = data.pagination;
        spinner.style.display = 'none';
    })
    .catch(error => {
        console.error('Error fetching users:', error);
        spinner.style.display = 'none';
    });
}

</script>
@endsection
