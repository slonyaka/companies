<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository
{

    public function getUserCompanies(User $user): Collection|array
    {
        return Company::query()
            ->where('user_id', $user->getAttribute('id'))
            ->get();
    }

    public function create(array $input): Company
    {
        $company = new Company($input);
        $company->save();
        return $company;
    }

}
