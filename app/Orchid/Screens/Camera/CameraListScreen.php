<?php

namespace App\Orchid\Screens\Camera;

use App\Models\Camera;
use App\Orchid\Layouts\ReusableComponentListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class CameraListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Kameras';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Kameradaten';

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
            'cameras' => Camera::filters()
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
                ->route('platform.cameras.edit')
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
            new ReusableComponentListLayout('cameras', 'Kamera'),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $camera = Camera::findOrFail($request->get('id'));

        if ($camera->supplyUnit()->exists()) {
            Toast::error(__('Kamerastatus kann nicht geändert werden! Kamera ist Teil eines Systems!'));
        }

        $camera->broken = !$camera->broken;
        $camera->save();

        Toast::success(__('Status wurde geändert!!'));
    }

    public function remove(Request $request)
    {
        $camera = Camera::findOrFail($request->get('id'));

        if ($camera->projects() && $camera->projects()->where(['rec_end_date' => null])->get()->first() != null)
        {
            Alert::error(__('Diese Kamera ist einem Projekt zugewiesen und kann nicht gelöscht werden!!'));
        }
        else
        {
            $camera->delete();

            Toast::success(__('Kamera wurde gelöscht!'));
        }
    }
}
