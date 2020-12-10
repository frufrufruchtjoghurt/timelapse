<?php

namespace App\Orchid\Screens\Ups;

use App\Models\Ups;
use App\Orchid\Layouts\ReusableComponentListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class UpsListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'USV';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller USVs';

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
            'upss' => Ups::filters()
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
                ->route('platform.ups.edit')
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
            new ReusableComponentListLayout('upss', 'USV'),
        ];
    }

    public function changeBrokenStatus(Request $request)
    {
        $ups = Ups::findOrFail($request->get('id'));

        $ups->broken = !$ups->broken;
        $ups->save();

        Toast::success(__('Ups status has been changed!'));
    }

    public function remove(Request $request)
    {
        $ups = Ups::findOrFail($request->get('id'));

        if ($ups->system()->get()->first() != null)
        {
            Alert::error(__('Unable to delete ups assigned to a system!'));
        }
        else
        {
            $ups->delete();

            Toast::success(__('Ups has been deleted!'));
        }
    }
}
