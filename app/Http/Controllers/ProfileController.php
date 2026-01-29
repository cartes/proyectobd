<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController
{
    public function index(Request $request)
    {
        // Example usage
        $isActive = true;
        if (!$isActive) {
            return response()->json(['message' => 'Inactive']);
        }

        return response()->json(['message' => 'Active']);
    }
}