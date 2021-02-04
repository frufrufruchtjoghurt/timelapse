<?php

namespace App\Orchid\Layouts\Project;

use Illuminate\Support\Facades\Log;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout;

class ProjectCompaniesListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'companies.',
        'users.'
    ];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncGetUsers';

    /**
     * @return Layout[]
     */
    protected function layouts(): array
    {
        if ($this->query == null || !$this->query->has('companyUsers'))
        {
            return [];
        }

        if ($this->query->has('features'))
        {
            return [
                Layout::rows([
                    Select::make('users.')
                        ->title(__('Projektkunden'))
                        ->multiple()
                        ->required()
                        ->options($this->query->get('companyUsers'))
                        ->canSee($this->query->has('companyUsers')),

                ])->title(__('Projektkunden')),

                $this->query->getContent('features'),
            ];
        }

        return [
            Layout::rows([
                Select::make('users.')
                    ->title(__('Projektkunden'))
                    ->multiple()
                    ->required()
                    ->options($this->query->get('companyUsers'))
                    ->canSee($this->query->has('companyUsers')),
            ])->title(__('Projektkunden')),
        ];
    }
}
