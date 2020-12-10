<?php

namespace App\Orchid\Screens\Router;

use App\Models\Router;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class RouterEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Router erstellen';

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
     * @param Router $router
     *
     * @return array
     */
    public function query(Router $router): array
    {
        $this->exists = $router->exists;

        if ($this->exists) {
            $this->name = __('Router bearbeiten');
            $this->broken = $router->broken;
        }

        return [
            'router' => $router,
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
            Button::make(__('Create router'))
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
                ->confirm(__('Are you sure you want to delete the router?')),
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
            new ReusableComponentEditLayout('router', $this->broken, $this->exists)
        ];
    }

    public function createOrUpdate(Router $router, Request $request)
    {
        $request->validate([
            'router.serial_nr' => 'required',
            'router.model' => 'required',
            'router.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $router->fill($request->get('router'));
        $router->broken = $this->exists ? $request->get('router.broken') : $this->broken;

        $router->save();

        Toast::info(__('Router was saved.'));

        return redirect()->route('platform.routers');
    }

    public function remove(Request $request)
    {
        $router = Router::findOrFail($request->get('id'));

        if ($router->system()->get()->first() != null)
        {
            Alert::error(__('Unable to delete router assigned to a system!'));
        }
        else
        {
            $router->delete();

            Toast::success(__('Router has been deleted!'));

            return redirect()->route('platform.routers');

        }
    }
}
