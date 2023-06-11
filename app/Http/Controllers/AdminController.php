<?php

namespace App\Http\Controllers;

use App\Models\admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTFactory;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.guard:admins');
    }

    public function login()
    {
        $credentials = request(['name', 'password']);

        if (!$token = auth()->guard('admins')->attempt($credentials)) {
            return response()->json(['error' => 'unAuthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('admins')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('admins')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('admins')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTFactory::getTTL() * 60
        ]);
    }

    public function update(Request $request) {
        $info = Auth('admins')->user();

        $validate = $request->validate(['name' => 'required|min:10']);

        if (!$validate) {
            return response()->json(['error' => $validate], 401);
        }

        $admin = admin::find($info->getAuthIdentifier());
        $admin->name = $request->name;
        $admin->save();

        return response()->json(['message'=>'Profile updated Successfully!'], 200);
    }

    public function updatePassword(Request $request) {
        $info = Auth('admins')->user();

        $admin = admin::find($info->getAuthIdentifier());

        $validate = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required_with:new_password_confirmation|same:new_password_confirmation|min:10',
            'new_password_confirmation' => 'required'
        ]);

        if (!$validate) {
            return response()->json($validate->errors()->toJson(), 400);
        }

        if (!Hash::check($request->old_password, $admin->password)) {
            return response()->json(['message'=>'try to enter the old password correctly'], 400);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return response()->json('The password has been updated Successfully!', 200);
    }
}
