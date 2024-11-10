@extends('theme.layout')
@section('content')

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-3"><i class="fas fa-arrow-left"></i></a>
                <h2 class="mb-0">{{ Illuminate\Support\Str::title($user->firstname) }}'s Orders</h2>
            </div>
            
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                Add Orders
            </button>
        </div>
        
        @if ($orders->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No orders available.
            </div>
        @else
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
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $order->product->image) }}" alt="{{ $order->product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px;">
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

            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links('vendor.pagination.bootstrap-4') }}
            </div>
        @endif
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
                                    <input type="hidden" name="user_id" value="{{ $user->id}}">
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

@endsection
