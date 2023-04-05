<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Companies Endpoint
 */
class CompanyController extends Controller
{

    public function __construct(
        private AuthService $authService,
        private CompanyService $companyService
    ) {

    }

    /**
     * Get list of user`s companies
     *
     * GET /api/user/companies
     * Authorization: api_token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(): JsonResponse
    {
        $user = $this->authService->getCurrentUser();
        $companies = $this->companyService->getUserCompanies($user);
        return new JsonResponse(['status' => true, 'companies' => $companies]);
    }

    /**
     * Create a company for authenticated user
     *
     * POST /api/user/companies
     * Content-Type: application/json
     * Authorization: api_token
     *
     * {"title": "title", "phone": "phone", "description": "description"}
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request): JsonResponse
    {

        $input = $this->validate($request, [
            'title' => 'required',
            'phone' => 'required',
            'description' => 'required',
        ]);

        $user = $this->authService->getCurrentUser();

        $input['user_id'] = $user->getAttribute('id');

        $company = $this->companyService->create($input);

        if ($company) {
            return new JsonResponse(['status' => true], 201);
        }

        return new JsonResponse(['status' => false], 400);
    }

}
