<?php

namespace App\Http\Controllers\Auth;

use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class RegisterController extends Controller
{
    use ApiHelpers;

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), $this->userValidatedRules());

        if ($request->avatar){
            $base64Banner = $request->avatar;
            $imageData = base64_decode($base64Banner);

            $imageName = Str::random(10) . '.png'; // Générez un nom de fichier aléatoire
            $path = '/avatars/' . $imageName;

            Storage::disk('public')->put($path, $imageData);
        } else {
            $path = null;
        }
            if ($validator->passes()) {
                // Create New Writer
                $user = User::create([
                    'lastname' => $request->lastname,
                    'firstname' => $request->firstname,
                    'pseudo' => $request->pseudo ?? null,
                    'avatar' => 'storage' . $path,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);

                $writerToken = $user->createToken('auth_token', ['user'])->plainTextToken;
                return $this->onSuccess($writerToken, 'User Created With User Role');
            }


        if ($validator->passes()) {
            // Create New Writer
            $user = User::create([
                'lastname' => $request->lastname,
                'firstname' => $request->firstname,
                'pseudo' => $request->pseudo,
                'avatar' => 'https://picsum.photos/200',
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $writerToken = $user->createToken('auth_token', ['user'])->plainTextToken;
            return $this->onSuccess($writerToken, 'User Created With User Role');
        }
        return $this->onError(400, $validator->errors());
    }
}
