<?php

namespace App\Orchid\Layouts\User;

use App\Orchid\Filters\CompanyFilter;
use App\Orchid\Filters\UserFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class UserFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            UserFilter::class,
        ];
    }
}
