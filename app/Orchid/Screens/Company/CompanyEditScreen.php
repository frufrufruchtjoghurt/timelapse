<?php

namespace App\Orchid\Screens\Company;

use App\Models\Address;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CompanyEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Create Company';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Name and Address of a Company';

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
     * Query data.
     *
     * @param Company $company
     *
     * @return array
     */
    public function query(Company $company): array
    {
        $this->exists = $company->exists;

        if ($this->exists) {
            $this->name = __('Edit company');
        }

        return [
            'company' => $company,
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
            Button::make(__('Create company'))
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
                ->confirm(__('Are you sure you want to delete the company?')),
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
            Layout::rows([
                Input::make('company.name')
                    ->title(__('Company name'))
                    ->type('text')
                    ->required(),

                Group::make([
                    Relation::make('company.aid')
                        ->title(__('Address'))
                        ->fromModel(Address::class, 'street')
                        ->displayAppend('full')
                        ->required(),

                    ModalToggle::make(__('Add Address'))
                        ->modal('addNewAddress')
                        ->icon('map')
                        ->method('saveAddress'),
                ])->fullWidth(),
            ]),

            Layout::modal('addNewAddress', [
                Layout::rows([
                    Group::make([
                        Input::make('address.street')
                            ->title(__('Street'))
                            ->type('text')
                            ->autofocus()
                            ->required(),

                        Input::make('address.street_nr')
                            ->title(__('Street number'))
                            ->type('text')
                            ->required(),

                        Input::make('address.staircase')
                            ->title(__('Staircase'))
                            ->type('number'),

                        Input::make('address.door_nr')
                            ->title(__('Door number'))
                            ->type('number'),
                    ]),

                    Group::make([
                        Input::make('address.postcode')
                            ->title(__('Postcode'))
                            ->type('number')
                            ->required(),

                        Input::make('address.city')
                            ->title(__('City'))
                            ->type('text')
                            ->required(),
                    ]),

                    Group::make([
                        Input::make('address.region')
                            ->title(__('Region'))
                            ->type('text'),

                        Input::make('address.country')
                            ->title(__('Country'))
                            ->type('text')
                            ->required(),
                    ]),

                ])
            ])->title(__('New Address'))
                ->size(Modal::SIZE_LG)
                ->applyButton(__('Save'))
                ->closeButton(__('Cancel')),
        ];
    }

    /**
     * Save new Address
     *
     * @var Request
     */
    public function saveAddress(Request $request)
    {
        $request->validate([
            'address.street' => 'required',
            'address.street_nr' => 'required',
            'address.postcode' => 'required',
            'address.city' => 'required',
            'address.country' => 'required',
        ]);

        $address = new Address();

        $address->fill($request->input('address'));

        if ($address->exists)
        {
            Toast::error('Address already exists!');
        }
        else
        {
            $address->save();
            Toast::info(__('Address was saved.'));
        }

    }

    public function createOrUpdate(Company $company, Request $request)
    {
        $request->validate([
            'company.name' => 'required',
            'company.aid' => 'required',
        ]);

        if ($company->exists)
        {
            $old_address = $company->address()->get()->first();
            $company->fill($request->get('company'))->save();
            Toast::success(__('Company has been successfully changed.'));

            if ($old_address->companies()->get()->first() == null)
            {
                $old_address->delete();

                Alert::info(__('A now unused address has been deleted'));
            }
        }
        else
        {
            $request->validate([
               'company.name' => 'unique:companies,name'
            ]);
            $company->fill($request->get('company'))->save();
            Toast::success(__('The company has been created!'));
        }

        return redirect()->route('platform.companies');
    }

    public function remove(Company $company)
    {
        if ($company->users()->get()->first() != null)
        {
            Toast::error(__('Please remove all users assigned to this company before deleting the company!'));
        }
        else
        {
            $address = $company->address()->get()->first();
            try {
                $company->delete();
            } catch (Exception $e) {
                error_log($e);
                Alert::error(__('An error occurred while deleting the company. Please check your connection and your data'));
            }

            Toast::success(__('Company has been deleted!'));

            if ($address->companies()->get()->first() == null)
            {
                $address->delete();

                Alert::info(__('A now unused address has been deleted'));
            }

            return redirect()->route('platform.companies');
        }
    }
}
