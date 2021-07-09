<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\User;
use App\Orchid\Presenters\UserPresenter;
//use Orchid\Platform\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::make('last_name', __('Benutzer'))
                ->sort()
                ->cantHide()
                ->render(function (User $user) {
                    return new Persona($user->presenter());
                }),

            TD::make('email', __('E-Mail'))
                ->cantHide()
                ->render(function (User $user) {
                    return $user->email;
                }),

            TD::make('phone_nr', __('Telefonnr.'))
                ->cantHide()
                ->render(function (User $user) {
                    return $user->phone_nr;
                }),

            TD::make('name', __('Firma'))
                ->render(function (User $user) {
                    return new Persona($user->company()->get()->first()->presenter());
                }),

            TD::make('updated_at', __('Zuletzt Bearbeitet'))
                ->sort()
                ->render(function (User $user) {
                    if ($user->updated_at == null)
                    {
                        return __('Nie');
                    }
                    return $user->updated_at->toDateTimeString();
                }),

            TD::make(__('Passwort'))
                ->align(TD::ALIGN_CENTER)
                ->render(function (User $user) {
                    if ($user->email_verified_at == null) {
                        return Button::make(__('Passwort aussenden'))
                            ->method('verifyPasswordRequest')
                            ->confirm('Möchten Sie das Passwort für ' . $user->last_name . ' ' . $user->first_name
                                . ' wirklich an ' . $user->email . ' senden?')
                            ->parameters([
                                'id' => $user->id,
                            ])
                            ->disabled(!(Auth::user()->hasAccess('admin') || (!$user->hasAccess('manager') && !$user->hasAccess('admin'))))
                            ->icon('envelope');
                    }
                    return Button::make(__('Erneut senden (Bereits ' . $user->password_count . '-mal gesendet)'))
                        ->method('verifyPasswordRequest')
                        ->confirm('Möchten Sie das Passwort für ' . $user->last_name . ' ' . $user->first_name
                            . ' an ' . $user->email . ' wirklich erneut senden?')
                        ->parameters([
                            'id' => $user->id,
                        ])
                        ->disabled(!(Auth::user()->hasAccess('admin') || (!$user->hasAccess('manager') && !$user->hasAccess('admin'))))
                        ->icon('envelope-letter');
                }),

            TD::make(__('Aktionen'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (User $user) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Bearbeiten'))
                                ->route('platform.users.edit', $user->id)
                                ->icon('pencil'),

                            Button::make(__('Löschen'))
                                ->method('remove')
                                ->confirm(__('Möchten Sie diesen Kunden wirklich löschen?'))
                                ->parameters([
                                    'id' => $user->id,
                                ])
                                ->icon('trash'),
                        ])
                        ->canSee(Auth::user()->id != $user->id && (Auth::user()->hasAccess('admin') || (!$user->hasAccess('manager') && !$user->hasAccess('admin'))));
                }),
        ];
    }
}
