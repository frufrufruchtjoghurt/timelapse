<?php

declare(strict_types=1);

namespace App\Orchid\Presenters;

use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class UserPresenter extends Presenter implements Searchable, Personable
{
    /**
     * @return string
     */
    public function label(): string
    {
        return 'Users';
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->entity->first_name;
    }

    /**
     * @return string
     */
    public function surname():string
    {
        return $this->entity->last_name;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->name() . " " . $this->surname();
    }

    /**
     * @return string
     */
    public function subTitle(): string
    {
        $roles = null;

        if ($this->entity->hasAccess('admin'))
        {
            $roles = 'Administrator';
        }
        else if ($this->entity->hasAccess('manager'))
        {
            $roles = 'Manager';
        }

        return empty($roles)
            ? __('Standardnutzer')
            : $roles;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return route('platform.users.edit', $this->entity);
    }

    /**
     * @return string
     */
    public function image(): ?string
    {
        $hash = md5(strtolower(trim($this->entity->email)));

        return "https://www.gravatar.com/avatar/$hash?d=mp";

//        return null;
    }

    /**
     * The number of models to return for show compact search result.
     *
     * @return int
     */
    public function perSearchShow(): int
    {
        return 3;
    }

    /**
     * @param string|null $query
     *
     * @return Builder
     */
    public function searchQuery(string $query = null): Builder
    {
        return $this->entity->search($query);
    }
}
