<?php

namespace App\Orchid\Screens\SimCard;

use App\Models\SimCard;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SimCardEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Sim-Karte erstellen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Details';

    /**
     * @var array
     */
    public $permission = [
        'admin',
        'manager'
    ];

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @var bool
     */
    public $broken = false;

    /**
     * Query data.
     *
     * @param SimCard $simCard
     *
     * @return array
     */
    public function query(SimCard $simCard): array
    {
        $this->exists = $simCard->exists;

        if ($this->exists) {
            $this->name = __('Sim-Karte bearbeiten');
            $this->broken = $simCard->broken;
        }

        return [
            'simcard' => $simCard,
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make(__('Create sim-card'))
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make(__('Update'))
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make(__('Remove'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists)
                ->confirm(__('Are you sure you want to delete the sim-card?')),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('simcard.telephone_nr')
                    ->title(__('Telefonnummer'))
                    ->type('text')
                    ->required(),

                Group::make([
                    Input::make('simcard.contract')
                        ->title(__('Vertrag'))
                        ->type('text')
                        ->required(),

                    DateTimer::make('simcard.purchase_date')
                        ->title(__('Kaufdatum'))
                        ->allowInput()
                        ->format('Y-m-d')
                        ->required(),
                ]),

                CheckBox::make('simcard.broken')
                    ->placeholder(__('Defekt'))
                    ->value($this->broken)
                    ->canSee($this->exists),
            ]),
        ];
    }

    public function createOrUpdate(SimCard $simCard, Request $request)
    {
        $request->validate([
            'simcard.telephone_nr' => 'required',
            'simcard.contract' => 'required',
            'simcard.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $simCard->fill($request->get('simcard'));
        $simCard->broken = $this->exists ? $request->get('simcard.broken') : $this->broken;

        $simCard->save();

        Toast::info(__('Router was saved.'));

        return redirect()->route('platform.simcards');
    }

    public function remove(Request $request)
    {
        $simCard = SimCard::findOrFail($request->get('id'));

        if ($simCard->system()->get()->first() != null)
        {
            Alert::error(__('Unable to delete sim-card assigned to a system!'));
        }
        else
        {
            $simCard->delete();

            Toast::success(__('Sim-card has been deleted!'));

            return redirect()->route('platform.simcards');

        }
    }
}
