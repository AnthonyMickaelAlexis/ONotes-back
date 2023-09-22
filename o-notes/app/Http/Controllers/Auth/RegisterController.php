<?php

namespace App\Http\Controllers\Auth;

use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    use ApiHelpers;

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), $this->userValidatedRules());
            if ($validator->passes()) {
                // Create New Writer
                $user = User::create([
                    'lastname' => $request->lastname,
                    'firstname' => $request->firstname,
                    'pseudo' => $request->pseudo,
                    'avatar' => $request->avatar,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);

                $writerToken = $user->createToken('auth_token', ['user'])->plainTextToken;
                return $this->onSuccess($writerToken, 'User Created With User Role');
            }
        return $this->onError(400, $validator->errors());
    }
}