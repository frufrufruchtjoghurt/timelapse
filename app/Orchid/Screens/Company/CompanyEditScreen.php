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
    public $name = 'Firma erstellen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Firmenname, Anschrift, E-Mail und Telefonnummer einfügen';

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
            $this->name = __('Firma bearbeiten');
            if ($company->phone_nr) {
                $phone_nr = explode('/', $company->phone_nr);

                return [
                    'company' => $company,
                    'phone_country_code' => $phone_nr[0],
                    'phone_nr' => $phone_nr[1],
                ];
            }
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
            Button::make(__('Firma erstellen'))
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make(__('Änderungen speichern'))
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make(__('Löschen'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists)
                ->confirm(__('Möchten Sie diese Firma wirklich löschen?')),
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
                    ->title(__('Firmenname'))
                    ->type('text')
                    ->required(),

                Group::make([
                    Relation::make('company.address_id')
                        ->title(__('Adresse'))
                        ->fromModel(Address::class, 'street')
                        ->displayAppend('full')
                        ->required(),

                    ModalToggle::make(__('Adresse hinzufügen'))
                        ->modal('addNewAddress')
                        ->icon('map')
                        ->method('saveAddress'),
                ])->fullWidth(),

                Input::make('company.email')
                    ->type('email')
                    ->required()
                    ->title(__('E-Mail'))
                    ->placeholder(__('company@example.com')),

                Group::make([
                    Input::make('phone_country_code')
                        ->type('number')
                        ->title(__('Ländervorwahl')),

                    Input::make('phone_nr')
                        ->type('number')
                        ->title(__('Nummer')),
                ])
            ]),

            Layout::modal('addNewAddress', [
                Layout::rows([
                    Group::make([
                        Input::make('address.street')
                            ->title(__('Straße'))
                            ->type('text')
                            ->autofocus()
                            ->required(),

                        Input::make('address.street_nr')
                            ->title(__('Hausnummer'))
                            ->type('text')
                            ->required(),

                        Input::make('address.staircase')
                            ->title(__('Stiege'))
                            ->type('number'),

                        Input::make('address.door_nr')
                            ->title(__('Türnummer'))
                            ->type('number'),
                    ]),

                    Group::make([
                        Input::make('address.postcode')
                            ->title(__('PLZ'))
                            ->type('number')
                            ->required(),

                        Input::make('address.city')
                            ->title(__('Stadt'))
                            ->type('text')
                            ->required(),
                    ]),

                    Group::make([
                        Input::make('address.region')
                            ->title(__('Region'))
                            ->type('text'),

                        Input::make('address.country')
                            ->title(__('Land'))
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
            'company.address_id' => 'required',
        ]);

        if ($request->get('phone_country_code') || $request->get('phone_nr')) {
            $request->validate([
                'phone_country_code' => 'required',
                'phone_nr' => 'required',
            ]);
            $company->phone_nr = $request->get('phone_country_code') . '/' . $request->get('phone_nr');
        }

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
