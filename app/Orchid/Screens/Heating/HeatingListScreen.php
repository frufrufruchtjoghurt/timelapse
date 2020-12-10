<?php

namespace App\Orchid\Screens\Heating;

use App\Models\Heating;
use App\Orchid\Layouts\ReusableComponentListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class HeatingListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Heizungen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller Heizungen';

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
            'heatings' => Heating::filters()
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
            Link::make(__('Create new'))
                ->icon('pencil')
                ->route('platform.heatings.edit')
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
            new ReusableComponentListLayout('heatings', 'Heizung'),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $heating = Heating::findOrFail($request->get('id'));

        $heating->broken = !$heating->broken;
        $heating->save();

        Toast::success(__('Heating status has been changed!'));
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
        }
    }
}
