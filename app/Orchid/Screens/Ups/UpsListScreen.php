<?php

namespace App\Orchid\Screens\Ups;

use App\Models\Ups;
use App\Orchid\Layouts\ReusableComponentListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class UpsListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'USV';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller USVs';

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
            'upss' => Ups::filters()
                ->defaultSort('purchase_date', 'desc')
                ->paginate(),
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
                ->route('platform.ups.edit')
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
            new ReusableComponentListLayout('upss', 'USV'),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $ups = Ups::findOrFail($request->get('id'));

        if ($ups->supplyUnit()->exists()) {
            Toast::error(__('Status kann nicht geändert werden! USV ist Teil einer Versorgungseinheit!'));
        }

        $ups->broken = !$ups->broken;
        $ups->save();

        Toast::success(__('USV-Status wurde geändert!'));
    }

    public function remove(Request $request)
    {
        $ups = Ups::findOrFail($request->get('id'));

        if ($ups->supplyUnit()->get()->first() != null)
        {
            Alert::error(__('Diese USV ist einer Versorgungseinheit zugewiesen und kann nicht gelöscht werden!'));
        }
        else
        {
            $ups->delete();

            Toast::success(__('USV wurde gelöscht!'));
        }
    }
}
