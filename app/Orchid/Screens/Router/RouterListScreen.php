<?php

namespace App\Orchid\Screens\Router;

use App\Models\Router;
use App\Orchid\Layouts\ReusableComponentListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class RouterListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Router';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller Router';

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
            'routers' => Router::filters()
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
                ->route('platform.routers.edit')
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
            new ReusableComponentListLayout('routers', 'Router', false, true),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $router = Router::findOrFail($request->get('id'));

        if ($router->supplyUnit()->exists()) {
            Toast::error(__('Routerstatus kann nicht geändert werden! Router ist Teil einer Versorgungseinheit!'));
        }

        $router->broken = !$router->broken;
        $router->save();

        Toast::success(__('Router status has been changed!'));
    }

    public function remove(Request $request)
    {
        $router = Router::findOrFail($request->get('id'));

        if ($router->supplyUnit()->get()->first() != null)
        {
            Alert::error(__('Unable to delete router assigned to a system!'));
        }
        else
        {
            $router->delete();

            Toast::success(__('Router has been deleted!'));
        }
    }
}
