<?php

namespace App\Orchid\Screens\Fixture;

use App\Models\Fixture;
use App\Orchid\Layouts\ReusableComponentEditLayout;
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
            Button::make(__('Create fixture'))
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
                ->confirm(__('Are you sure you want to delete the fixture?')),
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
            new ReusableComponentEditLayout('fixture', $this->broken, $this->exists)
        ];
    }

    /**
     * @param Fixture $fixture
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Fixture $fixture, Request $request)
    {
        $request->validate([
            'fixture.serial_nr' => 'required',
            'fixture.model' => 'required',
            'fixture.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $fixture->fill($request->get('fixture'));
        $fixture->broken = $this->exists ? $request->get('fixture.broken') : $this->broken;

        $fixture->save();

        Toast::info(__('Fixture was saved.'));

        return redirect()->route('platform.fixtures');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        $fixture = Fixture::findOrFail($request->get('id'));

        if ($fixture->system()->get()->first() != null)
        {
            Alert::error(__('Unable to delete fixture assigned to a system!'));
        }
        else
        {
            $fixture->delete();

            Toast::success(__('Fixture has been deleted!'));

            return redirect()->route('platform.fixtures');

        }
    }
}
