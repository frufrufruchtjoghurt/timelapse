<?php

namespace App\Orchid\Screens\SupplyUnit;

use App\Models\Camera;
use App\Models\Cooling;
use App\Models\Fixture;
use App\Models\Heating;
use App\Models\Photovoltaic;
use App\Models\Router;
use App\Models\SimCard;
use App\Models\SupplyUnit;
use App\Models\Ups;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use App\Orchid\Layouts\ReusableSupplyUnitEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SupplyUnitEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Versorgungseinheit erstellen';

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
     * @param SupplyUnit|null $supplyunit
     *
     * @return array
     */
    public function query(SupplyUnit $supplyunit = null): array
    {
        $this->exists = $supplyunit != null ? $supplyunit->exists : false;

        if ($this->exists) {
            $this->name = __('Versorgungseinheit bearbeiten');
        }

        return [
            'supplyunit' => $supplyunit,
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
            Button::make(__('Versorgungseinheit erstellen'))
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make(__('Änderungen speichern'))
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),
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
                Input::make('supplyunit.serial_nr')
                    ->title(__('Seriennummer'))
                    ->type('text')
                    ->required(),
            ]),

            new ReusableSupplyUnitEditLayout('Gehäuse', 'fixture', Fixture::class),

            new ReusableSupplyUnitEditLayout('Router', 'router', Router::class),

            new ReusableSupplyUnitEditLayout('USV', 'ups', Ups::class, false),

            Layout::rows([
                Group::make([
                    CheckBox::make('supplyunit.has_heating')
                        ->title(__('Heizung')),

                    CheckBox::make('supplyunit.has_cooling')
                        ->title(__('Lüftung')),
                ])
            ]),

            new ReusableSupplyUnitEditLayout('Photovoltaik', 'photovoltaic', Photovoltaic::class, false),

            Layout::rows([
                Relation::make('cameras.')
                    ->title(__('Kameras'))
                    ->fromModel(Camera::class, 'model')
                    ->multiple()
                    ->applyScope('unit')
                    ->displayAppend('full'),

                TextArea::make('supplyunit.details')
                    ->title(__('Details')),
            ])->title(__('System'))
        ];
    }

    public function createOrUpdate(SupplyUnit $supplyunit, Request $request)
    {
        $request->validate([]);

        $supplyunit->fill($request->get('supplyunit'));

        $su = $request->get('supplyunit');

        if (array_key_exists('has_heating', $su)) {
            $supplyunit->has_heating = true;
        } else {
            $supplyunit->has_heating = false;
        }

        if (array_key_exists('has_cooling', $su)) {
            $supplyunit->has_cooling = true;
        } else {
            $supplyunit->has_cooling = false;
        }

        $supplyunit->save();

        $cameras = $request->get('cameras');

        if (!empty($cameras)) {
            foreach ($cameras as $id) {
                $camera = Camera::findOrFail($id);
                $camera->supply_unit_id = $supplyunit->id;
                $camera->save();
            }
        }

        Toast::info(__('Versorgungseinheit wurde gespeichert.'));

        return redirect()->route('platform.supplyunits');
    }
}
