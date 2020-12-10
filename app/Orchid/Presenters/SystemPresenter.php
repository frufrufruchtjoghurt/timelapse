<?php

namespace App\Orchid\Presenters;

use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Personable;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class SystemPresenter extends Presenter  implements Searchable, Personable
{
    /**
     * @return string
     */
    public function label(): string
    {
        return __('Systeme');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('System ') . $this->entity->id;
    }

    /**
     * @return string
     */
    public function subTitle(): string
    {
        $subtitle = $this->entity->fixture()->get()->first()->model . "\n" .
            $this->entity->router()->get()->first()->model . "\n" .
            $this->entity->sim_card()->get()->first()->model . "\n" .
            $this->entity->ups()->get()->first()->model;

        if ($this->entity->heating()->exists())
        {
            $subtitle .= "\n" . $this->entity->heating()->get()->first()->model;
        }

        if ($this->entity->photovoltaic()->exists())
        {
            $subtitle .= "\n" . $this->entity->photovoltaic()->get()->first()->model;
        }

        return $subtitle;
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return route('platform.systems.edit', $this->entity);
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
