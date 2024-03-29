<?php

namespace App\Orchid\Screens\Photovoltaic;

use App\Models\Photovoltaic;
use App\Orchid\Layouts\ReusableComponentListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class PhotovoltaicListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Photovoltaikanlagen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller Photovoltaikanlagen';

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
            'photovoltaics' => Photovoltaic::filters()
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
                ->route('platform.photovoltaics.edit')
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
            new ReusableComponentListLayout('photovoltaics', 'Photovoltaikanlagen'),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $photovoltaic = Photovoltaic::findOrFail($request->get('id'));

        if ($photovoltaic->supplyUnit()->exists()) {
            Toast::error(__('Status kann nicht geändert werden! Photovoltaikanlage ist Teil einer Versorgungseinheit!'));
        }

        $photovoltaic->broken = !$photovoltaic->broken;
        $photovoltaic->save();

        Toast::success(__('Status wurde geändert!'));
    }

    public function remove(Request $request)
    {
        $photovoltaic = Photovoltaic::findOrFail($request->get('id'));

        if ($photovoltaic->supplyUnit()->get()->first() != null)
        {
            Alert::error(__('Diese Photovoltaikanlage ist einer Versorgungseinheit zugewiesen und kann nicht gelöscht werden!'));
        }
        else
        {
            $photovoltaic->delete();

            Toast::success(__('Photovoltaikanlage wurde gelöscht!'));
        }
    }
}
