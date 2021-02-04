<?php

namespace App\Orchid\Screens\Fixture;

use App\Models\Fixture;
use App\Orchid\Layouts\ReusableComponentListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class FixtureListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Gehäuse';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller Gehäuse';

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
            'fixtures' => Fixture::filters()
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
                ->route('platform.fixtures.edit')
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
            new ReusableComponentListLayout('fixtures', 'Gehäuse'),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $fixture = Fixture::findOrFail($request->get('id'));

        if ($fixture->supplyUnit()->exists()) {
            Toast::error(__('Status kann nicht geändert werden! Gehäuse ist Teil einer Versorgungseinheit!'));
        }

        $fixture->broken = !$fixture->broken;
        $fixture->save();

        Toast::success(__('Status wurde geändert!'));
    }

    public function remove(Request $request)
    {
        $fixture = Fixture::findOrFail($request->get('id'));

        if ($fixture->supplyUnit()->get()->first() != null)
        {
            Alert::error(__('Dieses Gehäuse ist einer Versorgungseinheit zugewiesen und kann nicht gelöscht werden!'));
        }
        else
        {
            $fixture->delete();

            Toast::success(__('Gehäuse wurde gelöscht!'));
        }
    }
}
