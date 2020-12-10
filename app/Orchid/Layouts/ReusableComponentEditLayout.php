<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class ReusableComponentEditLayout extends Rows
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
    private $prefix;

    /**
    * @var bool
    */
    private $broken;

    /**
     * @var bool
     */
    private $visible = false;

    public function __construct(string $prefix, bool $broken = false, bool $visible = false)
    {
        $this->prefix = $prefix;
        $this->broken = $broken;
        $this->visible = $visible;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            Input::make($this->prefix . '.serial_nr')
                ->title(__('Seriennummer'))
                ->type('text')
                ->required(),

            Group::make([
                Input::make($this->prefix . '.model')
                    ->title(__('Modell'))
                    ->type('text')
                    ->required(),

                DateTimer::make($this->prefix . '.purchase_date')
                    ->title(__('Kaufdatum'))
                    ->allowInput()
                    ->format('Y-m-d')
                    ->required()
            ]),

            CheckBox::make($this->prefix . '.broken')
                ->placeholder(__('Defekt'))
                ->value($this->broken)
                ->canSee($this->visible),
        ];
    }
}
