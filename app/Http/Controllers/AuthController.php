<?php

namespace App\Http\Controllers;

use App\Mail\PasswordRecovery;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Auth Endpoints
 */
class AuthController extends Controller
{

    public function __construct(
        private UserService $userService,
        private AuthService $authService
    ) {

    }

    /**
     * Register user. Returns an api_token for using in further requests.
     *
     * POST /api/user/register
     * Content-Type: application/json
     *
     * {"email": "email", "first_name": "first_name", "last_name": "last_name",
     * "phone": "phone", "password": "password"}
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): JsonResponse
    {

        $input = $this->validate($request, [
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'password' => 'required|min:8',
        ]);

        $input['password'] = Hash::make($input['password']);

        $user = $this->userService->create($input);

        if ($user->exists) {

            $this->userService->setApiToken($user,
                $this->authService->generateToken());

            return new JsonResponse([
                'status' => true,
                'api_token' => $user->getAttribute('api_token'),
            ], 201);
        }

        return new JsonResponse([
            'status' => false,
            'errors' => ['Bad request'],
        ], 400);
    }

    /**
     * Sign in user. Returns an api_token for using in further requests.
     * POST /api/user/sign-in
     * Content-Type: application/json
     *
     * {"email": "email", "password": "password"}
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signIn(Request $request): JsonResponse
    {

        $input = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $input['email'];

        if (empty($email)) {
            return new JsonResponse([
                'status' => false,
                'errors' => ['Email required'],
            ], 400);
        }

        $user = $this->userService->getByEmail($email);

        if (empty($user)) {
            return new JsonResponse([
                'status' => false,
                'errors' => ['User not found'],
            ], 401);
        }

        if (!Hash::check($input['password'], $user->getAttribute('password'))) {
            return new JsonResponse([
                'status' => false,
                'errors' => ['Invalid email or password'],
            ], 401);
        }

        $this->userService->setApiToken($user,
            $this->authService->generateToken());

        return new JsonResponse([
            'status' => true,
            'api_token' => $user->getAttribute('api_token'),
        ], 200);
    }

    /**
     * Recover password. Sends email to the user.
     *
     * POST http://api.loc/api/user/recover-password
     * Content-Type: application/json
     *
     * {"email": "email"}
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function recoverPassword(Request $request): JsonResponse
    {

        $input = $this->validate($request, [
            'email' => 'required|email',
        ]);

        try {

            $user = $this->userService->getByEmail($input['email']);

            if (empty($user)) {
                return new JsonResponse([
                    'status' => false,
                    'errors' => ['User not found'],
                ], 400);
            }

            $token = $this->authService->generatePasswordRecoveryToken();

            $this->userService->setPasswordRecoveryToken($user, $token);

            // here is a URL for the frontend which can accept request and show form with fields for a new password.
            $url = url('/update-password', ['token' => $token]);

            Mail::to($user->getAttribute('email'))
                ->send(new PasswordRecovery($url));

            return new JsonResponse(['status' => true], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => false,
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    /**
     * Update password
     *
     * POST http://api.loc/api/user/update-password
     * Content-Type: application/json
     *
     * {"token": "password_restore_token", "password": "password",
     * "password_confirmation": "password"}
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $input = $this->validate($request, [
            'token' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user
            = $this->userService->getUserByPasswordRestoreToken($input['token']);

        if (empty($user)) {
            return new JsonResponse([
                'status' => false,
                'errors' => ['Invalid token'],
            ], 400);
        }

        $password = Hash::make($input['password']);

        try {
            $this->userService->updatePassword($user, $password);

            return new JsonResponse(['status' => true], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => false,
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

}
