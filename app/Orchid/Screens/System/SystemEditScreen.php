<?php

namespace App\Orchid\Screens\System;

use App\Models\Fixture;
use App\Models\Heating;
use App\Models\Photovoltaic;
use App\Models\Router;
use App\Models\SimCard;
use App\Models\System;
use App\Models\Ups;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use App\Orchid\Layouts\ReusableSystemEditLayout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SystemEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'System erstellen';

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
     * Query data.
     *
     * @param System|null $system
     *
     * @return array
     */
    public function query(System $system = null): array
    {
        $this->exists = $system != null ? $system->exists : false;

        if ($this->exists) {
            $this->name = __('System bearbeiten');
        }

        return [
            'system' => $system,
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
            Button::make(__('Create system'))
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
                ->confirm(__('Are you sure you want to delete the system?')),
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
            new ReusableSystemEditLayout('Gehäuse', 'fixture', Fixture::class),

            Layout::modal('addNewGehäuse', [
                new ReusableComponentEditLayout('fixture'),
            ])->title(__('Gehäuse erstellen'))
                ->size(Modal::SIZE_LG)
                ->applyButton(__('Save'))
                ->closeButton(__('Cancel')),

            new ReusableSystemEditLayout('Router', 'router', Router::class),

            Layout::modal('addNewRouter', [
                new ReusableComponentEditLayout('router')
            ])->title(__('Router erstellen'))
                ->size(Modal::SIZE_LG)
                ->applyButton(__('Save'))
                ->closeButton(__('Cancel')),

            new ReusableSystemEditLayout('Sim-Karte', 'sim_card', SimCard::class),

            Layout::modal('addNewSim-Karte', [
                Layout::rows([
                    Input::make('sim_card.telephone_nr')
                        ->title(__('Telefonnummer'))
                        ->type('text')
                        ->required(),

                    Group::make([
                        Input::make('sim_card.contract')
                            ->title(__('Vertrag'))
                            ->type('text')
                            ->required(),

                        DateTimer::make('sim_card.purchase_date')
                            ->title(__('Kaufdatum'))
                            ->allowInput()
                            ->format('Y-m-d')
                            ->required(),
                    ]),

                    CheckBox::make('sim_card.broken')
                        ->value(false)
                        ->canSee(false),
                ]),
            ])->title(__('Sim-Karte erstellen'))
                ->size(Modal::SIZE_LG)
                ->applyButton(__('Save'))
                ->closeButton(__('Cancel')),

            new ReusableSystemEditLayout('USV', 'ups', Ups::class),

            Layout::modal('addNewUSV', [
                new ReusableComponentEditLayout('ups')
            ])->title(__('USV erstellen'))
                ->size(Modal::SIZE_LG)
                ->applyButton(__('Save'))
                ->closeButton(__('Cancel')),

            new ReusableSystemEditLayout('Heizung', 'heating', Heating::class, false),

            Layout::modal('addNewHeizung', [
                new ReusableComponentEditLayout('heating')
            ])->title(__('Heizung erstellen'))
                ->size(Modal::SIZE_LG)
                ->applyButton(__('Save'))
                ->closeButton(__('Cancel')),

            new ReusableSystemEditLayout('Photovoltaik', 'photovoltaic', Photovoltaic::class, false),

            Layout::modal('addNewPhotovoltaik', [
                new ReusableComponentEditLayout('photovoltaic')
            ])->title(__('Photovoltaic erstellen'))
                ->size(Modal::SIZE_LG)
                ->applyButton(__('Save'))
                ->closeButton(__('Cancel')),
        ];
    }

    public function createOrUpdate(System $system, Request $request)
    {
        $request->validate([]);

        $system->fill($request->get('system'))->save();

        Toast::info(__('System was saved.'));

        return redirect()->route('platform.systems');
    }

    public function saveComponent(string $modelName, string $prefix, string $name, Request $request)
    {
        if ($prefix == 'sim_card')
        {
            $request->validate([
                $prefix . '.telephone_nr' => 'required',
                $prefix . '.contract' => 'required',
                $prefix . '.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            ]);
        }
        else
        {
            $request->validate([
                $prefix . '.serial_nr' => 'required',
                $prefix . '.model' => 'required',
                $prefix . '.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            ]);
        }

        $component = new $modelName();

        $component->fill($request->get($prefix));
        $component->broken = false;

        $component->save();

        Toast::info(__($name . ' was saved.'));
    }

    public function remove(Request $request)
    {
        $system = System::findOrFail($request->get('id'));

        if ($system->projects()->get()->first() != null)
        {
            Alert::error(__('Unable to delete system assigned to an active project!'));
        }
        else
        {
            $system->delete();

            Toast::success(__('Router has been deleted!'));

            return redirect()->route('platform.systems');

        }
    }
}
