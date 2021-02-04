<?php

namespace App\Orchid\Layouts\Project;

use App\Models\SupplyUnit;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout as RowLayout;

class ProjectSystemsListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'project.start_date',
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
    protected $asyncMethod = 'asyncGetSystems';

    /**
     * @return Layout[]
     */
    protected function layouts(): array
    {
        if ($this->query == null || !$this->query->has('availableSystems')) {
            return [];
        }

        return [
            RowLayout::rows([
                Select::make('systems.')
                    ->title(__('Systeme'))
                    ->multiple()
                    ->required()
                    ->options($this->query->get('availableSystems'))
                    ->canSee($this->query->has('availableSystems')),

                Input::make('project.url')
                    ->title(__('URL'))
                    ->type('text')
                    ->required(),
            ])->title(__('Projektausstattung')),
        ];
    }
}
