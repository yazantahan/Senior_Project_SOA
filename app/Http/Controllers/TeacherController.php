<?php

namespace App\Http\Controllers;

use App\Models\teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.guard:teachers');
    }

    public function login(Request $request)
    {
        $credentials = request(['email','password']);

        if (!$token = auth()->guard('teachers')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
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
        return response()->json(auth()->guard('teachers')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('teachers')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('teachers')->refresh());
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
            'expires_in' => auth()->guard('teachers')->factory()->getTTL() * 60,
        ]);
    }

    public function update(Request $request) {
        $info = Auth('teachers')->user();

        $validate = $request->validate([
            'name' => 'required|min:10',
            'phone_number' => 'int',
            'email' => 'required|email'
        ]);

        if (!$validate) {
            return response()->json($validate->errors()->toJson(), 400);
        }

        $teacher = teacher::find($info->getAuthIdentifier());
        $teacher->name = $request->name;
        $teacher->phone_number = $request->phone_number;
        $teacher->email = $request->email;
        $teacher->save();

        return response()->json(['message'=>'Profile updated Successfully!'], 200);
    }

    public function updatePassword(Request $request) {
        $info = Auth('teachers')->user();

        $teacher = teacher::find($info->getAuthIdentifier());

        $validate = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required_with:new_password_confirmation|same:new_password_confirmation|min:6',
            'new_password_confirmation' => 'required'
        ]);

        if (!$validate) {
            return response()->json($validate->errors()->toJson(), 400);
        }

        if (!Hash::check($request->old_password, $teacher->password)) {
            return response()->json(['message'=>'try to enter the current password correctly'], 400);
        }

        $teacher->password = Hash::make($request->new_password);
        $teacher->save();

        return response()->json('The password has been updated Successfully!', 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::validate($request->all(),[
                'name' => 'required|string|between:10,20',
                'email' => 'required|string|max:100',
                'password' => 'required|string|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $teacher = new teacher;
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->password = Hash::make($request->password);
        $teacher->save();

        return response()->json([
            'message' => 'User successfully registered',
            'teacher' => $teacher
        ], 201);
    }
}
