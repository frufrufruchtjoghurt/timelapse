<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
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
                    ])->empty('')
                    ->help(__('Für \'Sonstiges\' kein Element auswählen.'))
                    ->required()
                    ->title(__('Anrede')),

                Input::make('user.title')
                    ->type('text')
                    ->title(__('Titel'))
                    ->placeholder(__('Mag.')),
            ])->autoWidth(),

            Group::make([
                Input::make('user.first_name')
                    ->type('text')
                    ->max(255)
                    ->required()
                    ->title(__('Vorname'))
                    ->placeholder(__('Vorname')),

                Input::make('user.last_name')
                    ->type('text')
                    ->max(255)
                    ->required()
                    ->title(__('Name'))
                    ->placeholder(__('Name')),
            ]),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),
        ];
    }
}
