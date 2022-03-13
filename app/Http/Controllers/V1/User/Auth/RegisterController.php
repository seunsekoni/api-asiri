<?php

namespace App\Http\Controllers\V1\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Models\Contributor;
use App\Models\Cordinator;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Register a new user to the application.
     *
     * @param RegisterRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->account_type == 'contributor') {
            $contributor = new Contributor();
            $contributor->user()->associate($user);
            $contributor->save();
        } else {
            $cordinator = new Cordinator();
            $cordinator->user()->associate($user);
            $cordinator->bvn = $request->bvn;
            $cordinator->bank_name = $request->bank_name;
            $cordinator->account_number = $request->account_number;
            $cordinator->bank_account_name = $request->bank_account_name;
            $cordinator->save();
        }

        event(new Registered($user));

        $token = $user->createToken('asiri_user')->plainTextToken;

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_CREATED)
            ->withMessage('User registration was successful!!!')
            ->withData([
                'user' => $user,
                'token' => $token
            ])
            ->build();
    }
}
