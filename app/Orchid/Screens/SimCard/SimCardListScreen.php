<?php

namespace App\Orchid\Screens\SimCard;

use App\Models\SimCard;
use App\Orchid\Layouts\ReusableComponentListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class SimCardListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Sim-Karten';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller Sim-Karten';

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
            'simcards' => SimCard::filters()
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
                ->route('platform.simcards.edit')
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
            new ReusableComponentListLayout('simcards', 'Sim-Karte', true),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $simCard = SimCard::findOrFail($request->get('id'));

        if ($simCard->router()->exists()) {
            Toast::error(__('Status kann nicht geändert werden! Sim-Karte ist Teil einer Versorgungseinheit!'));
        }

        $simCard->broken = !$simCard->broken;
        $simCard->save();

        Toast::success(__('Sim-Kartenstatus wurde geändert!'));
    }

    public function remove(Request $request)
    {
        $simCard = SimCard::findOrFail($request->get('id'));

        if ($simCard->router()->get()->first() != null)
        {
            Alert::error(__('Diese Sim-Karte ist einem Router zugewiesen und kann nicht gelöscht werden!'));
        }
        else
        {
            $simCard->delete();

            Toast::success(__('Sim-Karte wurde gelöscht!'));
        }
    }
}
