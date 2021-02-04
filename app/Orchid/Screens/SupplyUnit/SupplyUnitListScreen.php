<?php

namespace App\Orchid\Screens\SupplyUnit;

use App\Models\SupplyUnit;
use App\Orchid\Layouts\SupplyUnit\SupplyUnitListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class SupplyUnitListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Versorgungseinheiten';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller Versorgungseinheiten';

    /**
     * @var array
     */
    public $permission = [
        'admin',
        'manager'
    ];

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'supplyunits' => SupplyUnit::paginate(),
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
            Link::make(__('Erstellen'))
                ->icon('pencil')
                ->route('platform.supplyunits.edit')
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
            SupplyUnitListLayout::class,
        ];
    }

    public function remove(Request $request)
    {
        $supplyunit = SupplyUnit::findOrFail($request->get('id'));

        if ($supplyunit->projects()->where(['rec_end_date' => null])->get()->first() != null)
        {
            Alert::error(__('Diese Versorgungseinheit ist einem Projekt zugewiesen und kann nicht gelöscht werden!'));
        }
        else
        {
            $supplyunit->delete();

            Toast::success(__('Versorgungseinheit wurde gelöscht!'));
        }
    }
}
