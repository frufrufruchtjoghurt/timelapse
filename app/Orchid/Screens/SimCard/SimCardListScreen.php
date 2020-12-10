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
            Link::make(__('Create new'))
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
            new ReusableComponentListLayout('simcards', 'Sim-Karte'),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $simCard = SimCard::findOrFail($request->get('id'));

        $simCard->broken = !$simCard->broken;
        $simCard->save();

        Toast::success(__('Sim-Card status has been changed!'));
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

            Toast::success(__('Sim-Card has been deleted!'));
        }
    }
}
