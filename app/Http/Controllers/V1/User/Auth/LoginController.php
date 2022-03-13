<?php

namespace App\Http\Controllers\V1\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Login existing users to the application.
     *
     * @param LoginRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return ResponseBuilder::asError(Response::HTTP_UNAUTHORIZED)
                ->withMessage('Invalid login details')
                ->build();
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('asiri_user')->plainTextToken;
        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage('User login was successful.')
            ->withData([
                'user' => $user,
                'token' => $token
            ])
            ->build();
    }

    /**
     * Log user out from current device.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ResponseBuilder::asSuccess()
            ->withMessage('Logout was successful.')
            ->build();
    }
}
