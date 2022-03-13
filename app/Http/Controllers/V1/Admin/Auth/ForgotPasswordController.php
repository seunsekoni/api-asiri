<?php

namespace App\Http\Controllers\V1\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    /**
     * Send a reset link to the given user admin.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $status)
                : $this->sendResetLinkFailedResponse($status);
    }

    /**
     * Get the response for a successful sent password reset link.
     *
     * @param Request $request
     * @param string $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_OK)
            ->withMessage(trans($response))
            ->build();
    }

    /**
     * Get the response for a failed send password reset.
     *
     * @param Request $request
     * @param string $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function sendResetLinkFailedResponse($response)
    {
        return ResponseBuilder::asError(400)
            ->withHttpCode(Response::HTTP_BAD_REQUEST)
            ->withMessage(trans($response))
            ->build();
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(): PasswordBroker
    {
        return Password::broker('admins');
    }
}
