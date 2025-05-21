<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserData(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => __('messageApi.user not found')], 404);
        }

        return response()->json([
            'message' => __('messageApi.user found'),
            'user' => $user
        ], 200);
    }
}
