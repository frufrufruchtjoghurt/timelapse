<?php

namespace App\Orchid\Screens\Router;

use App\Models\Router;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Button::make(__('Router erstellen'))
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
            new ReusableComponentEditLayout('router', $this->broken, $this->exists, true, true)
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
        if ($router->supplyUnit()->exists()) {
            Toast::error(__('Status kann nicht geändert werden! Router ist Teil einer Versorgungseinheit!'));
        }
        $router->broken = $this->exists ? $request->get('router.broken') : $this->broken;

        if (!$this->exists) {
            $ruts = Router::all();
            $highest_id = 1;
            if ($ruts) {
                foreach ($ruts as $rut) {
                    $highest_id = $highest_id < explode('t', $rut->name)[1] + 1 ? explode('t', $rut->name)[1] + 1 : $highest_id;
                }
            }
            $router->name = 'rut' . sprintf("%03d", $highest_id);
        }

        $router->save();

        Toast::info(__('Router wurde gespeichert.'));

        return redirect()->route('platform.routers');
    }
}
