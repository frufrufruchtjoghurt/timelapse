<?php

namespace App\Orchid\Screens\Camera;

use App\Models\Camera;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
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
    public $name = 'Create Camera';

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

        if ($this->exists) {
            $this->name = __('Edit Camera');
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
            Button::make(__('Create camera'))
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
                ->confirm(__('Are you sure you want to delete the camera?')),
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
            new ReusableComponentEditLayout('camera', $this->broken, $this->exists)
        ];
    }

    public function createOrUpdate(Camera $camera, Request $request)
    {
        $request->validate([
            'camera.serial_nr' => 'required',
            'camera.model' => 'required',
            'camera.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $camera->fill($request->get('camera'));
        $camera->broken = $this->exists ? $request->get('camera.broken') : $this->broken;

        $camera->save();

        Toast::info(__('Camera was saved.'));

        return redirect()->route('platform.cameras');
    }

    public function remove(Request $request)
    {
        $camera = Camera::findOrFail($request->get('id'));

        if ($camera->projects()->where(['end_date' => null])->get()->first() != null)
        {
            Alert::error(__('Unable to delete camera assigned to active project!'));
        }
        else
        {
            $camera->delete();

            Toast::success(__('Camera has been deleted!'));

            return redirect()->route('platform.cameras');

        }
    }
}
