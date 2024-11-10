<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;

class ApiUserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $users = User::withCount('orders')
                    ->where('id', '!=', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

        return response()->json([
            'success' => true,
            'users' => $users
        ], 200);
    }

    public function show($id)
    {        
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $user = User::findOrFail($id);
        $orders = $user->orders()->orderBy('created_at', 'desc')->paginate(5);

        return response()->json([
            'success' => true,
            'user' => $user,
            'orders' => $orders,
        ], 200);
    }
}

