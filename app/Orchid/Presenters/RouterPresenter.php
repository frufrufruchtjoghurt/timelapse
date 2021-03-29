<?php

namespace App\Orchid\Presenters;

use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class RouterPresenter extends Presenter implements Searchable, Personable
{
    /**
     * @return string
     */
    public function label(): string
    {
        return __('Routers');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->entity->name;
    }

    /**
     * @return string
     */
    public function subTitle(): string
    {
        return $this->entity->serial_nr;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return route('platform.routers.edit', $this->entity);
    }

    /**
     * @return string
     */
    public function image(): ?string
    {
        return null;
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
