<?php

namespace App\Orchid\Screens\System;

use App\Models\System;
use App\Orchid\Layouts\System\SystemListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class SystemListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Systeme';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller Systeme';

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
            'systems' => System::paginate(),
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
                ->route('platform.systems.edit')
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
            SystemListLayout::class,
        ];
    }

    public function remove(Request $request)
    {
        $system = System::findOrFail($request->get('id'));

        if ($system->projects()->where(['end_date' => null])->get()->first() != null)
        {
            Alert::error(__('Unable to delete system assigned to active project!'));
        }
        else
        {
            $system->delete();

            Toast::success(__('System has been deleted!'));
        }
    }
}
