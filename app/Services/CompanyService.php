<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{

    public function __construct(private CompanyRepository $companyRepository)
    {
    }

    public function getUserCompanies(User $user): Collection|array
    {
        return $this->companyRepository->getUserCompanies($user);
    }

    public function create(array $input): Company
    {
        return $this->companyRepository->create($input);
    }

}
