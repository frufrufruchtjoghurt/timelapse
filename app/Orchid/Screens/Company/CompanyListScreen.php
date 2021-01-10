<?php

namespace App\Orchid\Screens\Company;

use App\Models\Company;
use App\Orchid\Layouts\Company\CompanyListLayout;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use \Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CompanyListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Firmen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Registrierte Firmen inklusive Anschrift';

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
            'companies' => Company::filters()
                ->defaultSort('name')
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
            Link::make(__('Hinzufügen'))
                ->icon('pencil')
                ->route('platform.companies.edit')
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
            CompanyListLayout::class,
        ];
    }

    public function remove(Request $request)
    {
        $company = Company::findOrFail($request->get('id'));

        if ($company->users()->get()->first() != null)
        {
            Alert::error(__('Please remove all users assigned to this company before deleting the company!'));
        }
        else
        {
            $address = $company->address()->get()->first();
            $company->delete();

            Toast::success(__('Company has been deleted!'));

            if ($address->companies()->get()->first() == null)
            {
                $address->delete();

                Alert::info(__('A now unused address has been deleted'));
            }
        }
    }
}
