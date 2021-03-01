<?php

namespace App\Orchid\Layouts;

use App\Models\SimCard;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
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
    private $visible;

    /**
     * @var bool
     */
    private $has_name;

    /**
     * @var bool
     */
    private $is_router;

    private $has_serial;

    public function __construct(string $prefix, bool $broken = false, bool $visible = false, bool $has_name = false,
        bool $is_router = false, bool $has_serial = true)
    {
        $this->prefix = $prefix;
        $this->broken = $broken;
        $this->visible = $visible;
        $this->has_name = $has_name;
        $this->is_router = $is_router;
        $this->has_serial = $has_serial;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            Group::make([
                Input::make($this->prefix . '.serial_nr')
                    ->title(__('Seriennummer'))
                    ->type('text')
                    ->canSee($this->has_serial)
                    ->required(),

                Input::make($this->prefix . '.name')
                    ->title(__('Name'))
                    ->type('text')
                    ->disabled(!Auth::user()->hasAccess('admin'))
                    ->canSee($this->has_name && $this->visible)
                    ->required(),
            ]),

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

            Relation::make($this->prefix . '.sim_card_id')
                ->title(__('Sim-Karte'))
                ->fromModel(SimCard::class, 'contract')
                ->displayAppend('full')
                ->applyScope('available')
                ->canSee($this->is_router),

            Group::make([
                Input::make($this->prefix . '.ssid')
                    ->title(__('SSID'))
                    ->type('text')
                    ->canSee($this->is_router),

                Input::make($this->prefix . '.psk')
                    ->title(__('PSK'))
                    ->type('text')
                    ->canSee($this->is_router)
                    ->required(),
            ]),

            CheckBox::make($this->prefix . '.broken')
                ->placeholder(__('Defekt'))
                ->value($this->broken)
                ->sendTrueOrFalse()
                ->canSee($this->visible),
        ];
    }
}
