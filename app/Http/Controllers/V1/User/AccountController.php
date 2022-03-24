<?php

namespace App\Http\Controllers\V1\User;

use App\Enums\MediaCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UpdateAccountRequest;
use App\Http\Requests\V1\User\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;

class AccountController extends Controller
{
    /**
     * Get authenticated user's details.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profile()
    {
        return ResponseBuilder::asSuccess()
            ->withMessage('User\'s Profile fetched successful!!!')
            ->withData([
                'user' => request()->user(),
            ])
            ->build();
    }

    /**
     * Update profile.
     *
     * @param UpdateAccountRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateAccountRequest $request)
    {
        $user = $request->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        if ($request->profile_picture) {
            $user->addMediaFromRequest('profile_picture')->toMediaCollection(MediaCollection::PROFILEPICTURE);
        }

        return ResponseBuilder::asSuccess()
            ->withMessage('User profile updated successfully.')
            ->withData([
                'user' => $user,
            ])
            ->build();
    }

    /**
     * Update user password.
     *
     * @param UpdatePasswordRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return ResponseBuilder::asSuccess()
            ->withMessage('User password updated successfully')
            ->withData(['user' => $user])
            ->build();
    }
}
