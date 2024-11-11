<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
    
        $users = User::withCount('orders')
                    ->where('id', '!=', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);
    
        if (request()->ajax()) {
            return response()->json([
                'users' => $users->items(),
                'pagination' => $users->links('vendor.pagination.bootstrap-4')->render(),
            ]);
        }
    
        return view('users.index', compact('users'));
    }
    
    public function show($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $user = User::with(['orders.product'])->findOrFail($id);
        $orders = $user->orders()->orderby('created_at', 'desc')->paginate(5);
        $products = Product::all();

        if (request()->ajax()) {
            return response()->json([
                'orders' => $orders->items(),
                'pagination' => $orders->links('vendor.pagination.bootstrap-4')->render(),
            ]);
        }
        
        return view('users.show', compact('user', 'products', 'orders'));
    }
}
