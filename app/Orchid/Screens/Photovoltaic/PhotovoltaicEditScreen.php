<?php

namespace App\Orchid\Screens\Photovoltaic;

use App\Models\Photovoltaic;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PhotovoltaicEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Photovoltaikanlage erstellen';

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
     * @param Photovoltaic $photovoltaic
     *
     * @return array
     */
    public function query(Photovoltaic $photovoltaic): array
    {
        $this->exists = $photovoltaic->exists;

        if ($this->exists) {
            $this->name = __('Photovoltaicanlage bearbeiten');
            $this->broken = $photovoltaic->broken;
        }

        return [
            'photovoltaic' => $photovoltaic,
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
            Button::make(__('Photovoltaik erstellen'))
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
            new ReusableComponentEditLayout('photovoltaic', $this->broken, $this->exists)
        ];
    }

    public function createOrUpdate(Photovoltaic $photovoltaic, Request $request)
    {
        $request->validate([
            'photovoltaic.serial_nr' => 'required',
            'photovoltaic.model' => 'required',
            'photovoltaic.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $exists = Photovoltaic::query()->where('id', '=', $photovoltaic->id)->exists();


        $photovoltaic->fill($request->get('photovoltaic'));
        $photovoltaic->broken = $exists ? $request->photovoltaic['broken'] : false;

        $photovoltaic->save();

        Toast::info(__('Photovoltaikanlage wurde gespeichert.'));

        return redirect()->route('platform.photovoltaics');
    }
}
