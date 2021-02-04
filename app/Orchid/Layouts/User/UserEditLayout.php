<?php

namespace App\Orchid\Layouts\User;

use App\Models\Company;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * @var bool
     */
    private $canEdit;

    /**
     * @var bool
     */
    private $hidden;

    /**
     * @var bool
     */
    private $exists;

    /**
     * UserEditLayout constructor.
     * @param bool $canEdit
     * @param bool $hidden
     * @param bool $exists
     */
    public function __construct(bool $canEdit = false, bool $hidden = true, bool $exists = false) {
        $this->canEdit = $canEdit;
        $this->hidden = $hidden;
        $this->exists = $exists;
    }

    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Group::make([
                Select::make('user.gender')
                    ->options([
                        'Herr' => 'Herr',
                        'Frau' => 'Frau',
                        'Sonstige' => 'Sonstige',
                    ])
                    ->required()
                    ->disabled($this->canEdit)
                    ->title(__('Anrede')),

                Input::make('user.title')
                    ->type('text')
                    ->title(__('Titel'))
                    ->disabled($this->canEdit)
                    ->placeholder(__('Mag.')),
            ])->autoWidth(),

            Group::make([
                Input::make('user.first_name')
                    ->type('text')
                    ->max(255)
                    ->required()
                    ->disabled($this->canEdit)
                    ->title(__('Vorname'))
                    ->placeholder(__('Vorname')),

                Input::make('user.last_name')
                    ->type('text')
                    ->max(255)
                    ->required()
                    ->disabled($this->canEdit)
                    ->title(__('Name'))
                    ->placeholder(__('Name')),
            ]),

            Group::make([
                Input::make('user.email')
                    ->type('email')
                    ->required()
                    ->disabled($this->canEdit)
                    ->title(__('Email'))
                    ->placeholder(__('Email')),
            ]),

            Group::make([
                Input::make('phone_country_code')
                    ->type('number')
                    ->title(__('Ländervorwahl'))
                    ->disabled($this->canEdit),

                Input::make('phone_nr')
                    ->type('number')
                    ->disabled($this->canEdit)
                    ->title(__('Nummer')),
            ]),

            Input::make('user.password')
                ->title(__('Passwort'))
                ->type('text')
                ->disabled($this->canEdit)
                ->hidden($this->canEdit)
                ->canSee($this->hidden && $this->exists),

            Relation::make('user.company_id')
                ->title(__('Firma'))
                ->fromModel(Company::class, 'name')
                ->disabled($this->canEdit)
                ->required(),
        ];
    }
}
