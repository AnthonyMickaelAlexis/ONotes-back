<?php

namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function isAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('admin');
        }

        return false;
    }

    protected function isUser($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('user') || $user->tokenCan('admin');
        }

        return false;
    }

    protected function onSuccess($data, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function onError(int $code, string $message = ''): JsonResponse
    {
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }

    protected function postValidationRules(): array
    {
        return [
            'title' => 'required|string',
            'subtitle' => 'string',
            'text_content' => 'required|string',
            'file_content' => 'string',
            'banner' => 'string',
            'subcategory_id' => 'required|integer',
        ];
    }

    protected function categoryValidationRules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    protected function subcategoryValidationRules($categoryId): array
    {
        if (!empty($categoryId)) {
            return [
                'name' => 'required|string',
                'category_id' => 'integer',
            ];
        }
        return [
            'name' => 'required|string',
            'category_id' => 'required|integer',
        ];
    }

    protected function tagValidationRules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    protected function userValidatedRules(): array
    {
        return [
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'pseudo' => ['string', 'max:255', 'unique:users'],
            'avatar' => ['string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
