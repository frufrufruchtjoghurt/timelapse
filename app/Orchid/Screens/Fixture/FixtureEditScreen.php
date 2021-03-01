<?php

namespace App\Orchid\Screens\Fixture;

use App\Models\Fixture;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class FixtureEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Gehäuse erstellen';

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
     * @param Fixture $fixture
     *
     * @return array
     */
    public function query(Fixture $fixture): array
    {
        $this->exists = $fixture->exists;

        if ($this->exists) {
            $this->name = __('Gehäuse bearbeiten');
            $this->broken = $fixture->broken;
        }

        return [
            'fixture' => $fixture,
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
            Button::make(__('Gehäuse erstellen'))
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
            new ReusableComponentEditLayout('fixture', $this->broken, $this->exists, false, false, false)
        ];
    }

    /**
     * @param Fixture $fixture
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createOrUpdate(Fixture $fixture, Request $request)
    {
        $request->validate([
            'fixture.model' => 'required',
            'fixture.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $exists = Fixture::query()->where('id', '=', $fixture->id)->exists();

        $fixture->fill($request->get('fixture'));
        if ($fixture->supplyUnit()->exists()) {
            Toast::error(__('Status kann nicht geändert werden! Gehäuse ist Teil einer Versorgungseinheit!'));
        }
        $fixture->broken = $exists ? $request->fixture['broken'] : false;

        $fixture->save();

        Toast::info(__('Gehäuse wurde gespeichert.'));

        return redirect()->route('platform.fixtures');
    }
}
