<?php

namespace App\Orchid\Screens\Ups;

use App\Models\Ups;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UpsEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'USV erstellen';

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
     * @param Ups $ups
     *
     * @return array
     */
    public function query(Ups $ups): array
    {
        $this->exists = $ups->exists;

        if ($this->exists) {
            $this->name = __('USV bearbeiten');
            $this->broken = $ups->broken;
        }

        return [
            'ups' => $ups,
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
            Button::make(__('USV erstellen'))
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
            new ReusableComponentEditLayout('ups', $this->broken, $this->exists)
        ];
    }

    public function createOrUpdate(Ups $ups, Request $request)
    {
        $request->validate([
            'ups.serial_nr' => 'required',
            'ups.model' => 'required',
            'ups.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $exists = Ups::query()->where('id', '=', $ups->id)->exists();

        $ups->fill($request->get('ups'));
        if ($ups->supplyUnit()->exists()) {
            Toast::error(__('Status kann nicht geändert werden! USV ist Teil einer Versorgungseinheit!'));
        }
        $ups->broken = $exists ? $request->upd['broken'] : false;

        $ups->save();

        Toast::info(__('USV gespeichert.'));

        return redirect()->route('platform.ups');
    }
}
