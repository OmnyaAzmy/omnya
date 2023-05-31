<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'    => ['required', 'integer', 'exists:products,id'],
            'star'    => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $review = new Review();
        $review->fill($validator->validated());
        $review->user_id = Auth::id();
        $review->save();

        return response($review, 200, ["added successfully"]);
    }
}
