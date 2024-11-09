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
            return redirect()->route('orders'); 
        }

        $users = User::withCount('orders')
                    ->where('id', '!=', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['orders.product'])->findOrFail($id);
        $orders = $user->orders()->orderby('created_at', 'desc')->paginate(5);

        $products = Product::all();
        return view('users.show', compact('user', 'products', 'orders'));
    }
}
