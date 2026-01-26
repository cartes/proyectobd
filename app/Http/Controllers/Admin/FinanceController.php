<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function transactions(Request $request)
    {
        $query = Transaction::with('user')->latest();

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            })->orWhere('mp_payment_id', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $transactions = $query->paginate(20);

        return view('admin.finance.transactions', compact('transactions'));
    }
}
