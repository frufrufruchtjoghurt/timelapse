<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\User;
use App\Orchid\Presenters\UserPresenter;
//use Orchid\Platform\Models\User;
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
            TD::set('last_name', __('Benutzer'))
                ->sort()
                ->cantHide()
                ->render(function (User $user) {
                    return new Persona($user->presenter());
                }),

            TD::set('email', __('Email'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function (User $user) {
                    return $user->email;
                }),

            TD::set('updated_at', __('Zuletzt Bearbeitet'))
                ->sort()
                ->render(function (User $user) {
                    if ($user->updated_at == null)
                    {
                        return __('Nie');
                    }
                    return $user->updated_at->toDateTimeString();
                }),

            TD::set(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (User $user) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.users.edit', $user->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the user?'))
                                ->parameters([
                                    'id' => $user->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
