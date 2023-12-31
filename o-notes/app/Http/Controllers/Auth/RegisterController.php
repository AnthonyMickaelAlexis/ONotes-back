<?php

namespace App\Http\Controllers\Auth;

use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class RegisterController extends Controller
{
    use ApiHelpers;

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), $this->userValidatedRules());

/*        // récupération de l'image et enregistrement dans le dossier public/img
        if ($request->avatar) {
            $avatar = $request->avatar;
            $avatar = str_replace('data:image/png;base64,', '', $avatar);
            $avatar = str_replace(' ', '+', $avatar);
            $imageName = Str::random(10) . '.' . 'png';

            // création du dossier img s'il n'existe pas
            if (!file_exists(public_path().'/img')){
                mkdir(public_path().'/img');
            }

            \File::put(public_path() . '/img/Avatar' . $imageName, base64_decode($avatar));
        }*/

            if ($validator->passes()) {
                // Create New Writer
                $user = User::create([
                    'lastname' => $request->lastname,
                    'firstname' => $request->firstname,
                    'pseudo' => $request->pseudo ?? null,
                    'avatar' => 'https://picsum.photos/200',
                    //'avatar' => isset($imageName) ? '/img/' . $imageName : null,
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
