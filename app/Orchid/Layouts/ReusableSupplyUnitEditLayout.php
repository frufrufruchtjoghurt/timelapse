<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Contracts\Fieldable;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class ReusableSupplyUnitEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title = null;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $modelName;

    /**
     * @var bool
     */
    private $required;

    public function __construct(string $name, string $prefix, string $modelName, bool $required = true)
    {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->modelName = $modelName;
        $this->required = $required;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            Relation::make('supplyunit.' . $this->prefix . '_id')
                ->fromModel($this->modelName, 'id')
                ->title(__($this->name))
                ->applyScope('available')
                ->displayAppend('full')
                ->required($this->required),
        ];
    }
}
