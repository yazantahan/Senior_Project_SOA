<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.guard:users');
    }

    public function list() {
        $users = User::all();

        return response()->json(["Users" => $users], 200);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->guard('users')->attempt($credentials)) {
            return response()->json(['error' => $token], 401);
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
        return response()->json(auth('users')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('users')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('users')->refresh());
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
        $info = Auth('users')->user();

        $validate = $request->validate([
            'name' => 'required|min:10',
            'phone_number' => 'int',
            'email' => 'required|email'
        ]);

        if (!$validate) {
            return response()->json($validate->errors()->toJson(), 400);
        }

        $user = User::find($info->getAuthIdentifier());
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;
        $user->save();

        return response()->json(['message'=>'Profile updated Successfully!'], 200);
    }

    public function updatePassword(Request $request) {
        $info = Auth('users')->user();

        $user = User::find($info->getAuthIdentifier());

        $validate = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required_with:new_password_confirmation|same:new_password_confirmation|min:6',
            'new_password_confirmation' => 'required'
        ]);

        if (!$validate) {
            return response()->json($validate->errors()->toJson(), 400);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message'=>'try to enter the current password currectly'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json('The password has been updated Successfully!', 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::validate($request->all(),[
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|max:100',
                'password' => 'required_with:password_confirmation|same:password_confirmation|string|min:6',
                'password_confirmation' => 'required'
            ]
        );

        if (!$validator) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function destroy($id) {
        User::destroy($id);

        return response()->json("User successfully deleted!", 200);
    }
}
