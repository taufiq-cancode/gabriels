@extends('theme.layout')
@section('content')
    <div class="container mt-5">

        @if ($orders->isEmpty())
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No orders available.
            </div>
        @else
            <h4 class="mb-4">Here are your orders</h4>

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
@endsection
