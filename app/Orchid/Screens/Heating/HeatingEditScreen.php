<?php

namespace App\Orchid\Screens\Heating;

use App\Models\Heating;
use App\Orchid\Layouts\ReusableComponentEditLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class HeatingEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Heizung erstellen';

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
     * @param Heating $heating
     *
     * @return array
     */
    public function query(Heating $heating): array
    {
        $this->exists = $heating->exists;

        if ($this->exists) {
            $this->name = __('Heizung bearbeiten');
            $this->broken = $heating->broken;
        }

        return [
            'heating' => $heating,
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
            Button::make(__('Create heating'))
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
                ->confirm(__('Are you sure you want to delete the heating?')),
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
            new ReusableComponentEditLayout('heating', $this->broken, $this->exists)
        ];
    }

    public function createOrUpdate(Heating $heating, Request $request)
    {
        $request->validate([
            'heating.serial_nr' => 'required',
            'heating.model' => 'required',
            'heating.purchase_date' => 'required|date_format:Y-m-d|before_or_equal:today',
        ]);

        $heating->fill($request->get('heating'));
        $heating->broken = $this->exists ? $request->get('heating.broken') : $this->broken;

        $heating->save();

        Toast::info(__('Heating was saved.'));

        return redirect()->route('platform.heatings');
    }

    public function remove(Request $request)
    {
        $heating = Heating::findOrFail($request->get('id'));

        if ($heating->system()->get()->first() != null)
        {
            Alert::error(__('Unable to delete heating assigned to a system!'));
        }
        else
        {
            $heating->delete();

            Toast::success(__('Heating has been deleted!'));

            return redirect()->route('platform.heatings');

        }
    }
}
