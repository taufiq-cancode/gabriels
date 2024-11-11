@extends('theme.layout')
@section('content')
<div class="container mt-5">
    @if ($orders->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No orders available.
        </div>
    @else
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <h3 class="mb-4">Orders</h3>
            </div>
            
            <div>
                @if(auth()->user()->role === 'admin')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                        Add Orders
                    </button>

                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        View Users
                    </a>
                @endif 
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Date Ordered</th>
                    </tr>
                </thead>
                <tbody id="orders-tbody">
                    @foreach ($orders as $order)
                    <tr>
                        <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                        <td>{{ $order->order_id }}</td>
                        <td class="d-flex align-items-center">
                            @if(Storage::disk('public')->exists($order->product->image))
                                <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                            @endif
                            <span>{{ $order->product->name }}</span>
                        </td>
                        <td>{{ $order->quantity }}</td>
                        <td>${{ number_format($order->product->price * $order->quantity, 2) }}</td>
                        <td>{{ $order->status ?? 'Pending' }}</td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div id="pagination-container" class="d-flex justify-content-center mt-3">
            {{ $orders->links('vendor.pagination.bootstrap-4') }}
        </div>
    @endif

    <!-- Loading spinner -->
    <div id="loading-spinner" style="display: none; text-align: center; margin-top: 20px;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderModalLabel">Add Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addOrdersForm" method="POST" action="{{ route('admin.orders.store') }}">
                    @csrf
                    <div id="orderFieldsContainer">
                        <!-- User selection field -->
                        <div class="order-field-group mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="user_id" class="form-label">Select User</label>
                                    <select name="user_id" id="user_id" class="form-control" required>
                                        <option value="">Choose a user</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ Illuminate\Support\Str::title($user->firstname) }} {{ Illuminate\Support\Str::title($user->lastname) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product selection and quantity fields -->
                        <div class="order-field-group mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="product_id[]" class="form-label">Select Product</label>
                                    <select name="product_id[]" class="form-control" required>
                                        <option value="">Choose a product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="quantity[]" class="form-label">Quantity</label>
                                    <input type="number" name="quantity[]" class="form-control" required min="1">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-success add-order-field">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Orders</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderFieldsContainer = document.getElementById('orderFieldsContainer');
        const productOptions = `@foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach`;

        document.querySelector('.add-order-field').addEventListener('click', function () {
            const orderFieldGroup = document.createElement('div');
            orderFieldGroup.classList.add('order-field-group', 'mb-3');

            orderFieldGroup.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <label for="product_id[]" class="form-label">Select Product</label>
                        <select name="product_id[]" class="form-control" required>
                            <option value="">Choose a product</option>
                            ${productOptions}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="quantity[]" class="form-label">Quantity</label>
                        <input type="number" name="quantity[]" class="form-control" required min="1">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-order-field">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            `;

            orderFieldsContainer.appendChild(orderFieldGroup);

            // Attach event listener to remove button for this new order field group
            orderFieldGroup.querySelector('.remove-order-field').addEventListener('click', function () {
                orderFieldGroup.remove();
            });
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('pagination-container').addEventListener('click', function(event) {
            event.preventDefault();
            if (event.target.tagName === 'A') {
                const url = event.target.href;
                fetchOrders(url);
            }
        });
    });

    function fetchOrders(url) {
        const spinner = document.getElementById('loading-spinner');
        const ordersTbody = document.getElementById('orders-tbody');
        const paginationContainer = document.getElementById('pagination-container');

        // Show spinner and clear table temporarily
        spinner.style.display = 'block';
        ordersTbody.innerHTML = '';
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
            const startSerial = (currentPage - 1) * 5; // 5 items per page as in your pagination

            let ordersHtml = '';
            data.orders.forEach((order, index) => {
                ordersHtml += `
                    <tr>
                        <td>${startSerial + index + 1}</td>
                        <td>${order.order_id}</td>
                        <td class="d-flex align-items-center">
                            <img src="/storage/${order.product.image}" alt="${order.product.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
                            <span>${order.product.name}</span>
                        </td>
                        <td>${order.quantity}</td>
                        <td>$${(order.product.price * order.quantity).toFixed(2)}</td>
                        <td>${order.status || 'Pending'}</td>
                        <td>${new Date(order.created_at).toLocaleDateString()}</td>
                    </tr>
                `;
            });
            ordersTbody.innerHTML = ordersHtml;

            // Update pagination links and hide spinner
            paginationContainer.innerHTML = data.pagination;
            spinner.style.display = 'none';
        })
        .catch(error => {
            console.error('Error fetching orders:', error);
            spinner.style.display = 'none';
        });
    }

</script>
@endsection
