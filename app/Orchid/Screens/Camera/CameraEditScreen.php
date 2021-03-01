<?php

namespace App\Orchid\Screens\Camera;

use App\Models\Camera;
use App\Models\SupplyUnit;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CameraEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Kamera erstellen';

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
     * @param Camera $camera
     *
     * @return array
     */
    public function query(Camera $camera): array
    {
        $this->exists = $camera->exists;

        if ($this->exists == null)
            $this->exists = false;

        if ($this->exists) {
            $this->name = __('Kamera bearbeiten');
            $this->broken = $camera->broken;
        }

        return [
            'camera' => $camera,
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
            Button::make(__('Kamera erstellen'))
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
            new ReusableComponentEditLayout('camera', $this->broken, $this->exists, true),

            /*Layout::rows([
                Relation::make('supply_unit')
                    ->title('Versorgungseinheit')
                    ->displayAppend('full')
                    ->fromModel(SupplyUnit::class, 'serial_nr'),
            ])->title('System'),*/
        ];
    }

    public function createOrUpdate(Camera $camera, Request $request)
    {
        $request->validate([
            'camera.serial_nr' => 'required',
            'camera.model' => 'required',
            'camera.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $exists = Camera::query()->where('name', '=', $camera->name)->exists();

        $camera->fill($request->get('camera'));
        if ($this->exists && $camera->supplyUnit()->exists() && $this->broken) {
            Toast::error(__('Kamerastatus kann nicht geändert werden! Kamera ist Teil eines Systems!'));
        }
        $camera->broken = $exists ? $request->camera['broken'] : false;

        if (!$exists) {
            $cams = Camera::all();
            $highest_id = 1;
            if ($cams) {
                foreach ($cams as $cam) {
                    $highest_id = $highest_id < explode('m', $cam->name)[1] + 1 ? explode('m', $cam->name)[1] + 1 : $highest_id;
                }
            }
            $camera->name = 'cam' . sprintf("%03d", $highest_id);
            Storage::disk('systems')->makeDirectory($camera->name);
        }

        $camera->save();

        Toast::info(__('Kamera wurde gespeichert.'));

        return redirect()->route('platform.cameras');
    }
}
