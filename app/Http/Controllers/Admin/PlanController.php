<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'sale_amount' => 'nullable|numeric|min:0|lt:amount',
            'sale_expires_at' => 'nullable|date|after:now',
            'is_active' => 'required|boolean',
            'description' => 'required|string',
        ]);

        $plan->update([
            'name' => $request->name,
            'amount' => $request->amount,
            'sale_amount' => $request->sale_amount,
            'sale_expires_at' => $request->sale_expires_at,
            'is_active' => $request->is_active,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.plans.index')
            ->with('success', "Plan '{$plan->name}' actualizado correctamente.");
    }
}
