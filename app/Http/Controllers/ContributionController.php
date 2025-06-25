<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContributionController extends Controller
{
   public function contribute(Request $request, $id): JsonResponse
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
        ]);

        // Find the cause
        $cause = Cause::find($id);

        if (!$cause) {
            return response()->json(['message' => 'Cause not found'], 404);
        }

        // Save the contribution
        $contribution = new Contribution();
        $contribution->cause_id = $cause->id;
        $contribution->name = $validated['name'];
        $contribution->email = $validated['email'];
        $contribution->amount = $validated['amount'];
        $contribution->save();

        return response()->json([
            'message' => 'Contribution successful',
            'data' => $contribution
        ], 201);
    }

}
