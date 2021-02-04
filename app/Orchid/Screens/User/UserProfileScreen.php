<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserEditLayout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
//use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserProfileScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Benutzerprofil';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Ihre Benutzereinstellungen und Informationen';

    /**
     * @var User
     */
    protected $user;

    /**
     * Query data.
     *
     * @param Request $request
     *
     * @return array
     */
    public function query(Request $request): array
    {
        $this->user = $request->user();

        if ($this->user->phone_nr) {
            $phone_nr = explode('/', $this->user->phone_nr);

            return [
                'user' => $this->user,
                'phone_country_code' => $phone_nr[0],
                'phone_nr' => $phone_nr[1],
            ];
        }

        return [
            'user' => $this->user,
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
            DropDown::make(__('Einstellungen'))
                ->icon('open')
                ->list([
                    ModalToggle::make(__('Passwort ändern'))
                        ->icon('lock-open')
                        ->method('changePassword')
                        ->modal('password'),
                ]),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            new UserEditLayout(true, false),

            Layout::modal('password', Layout::rows([
                Password::make('old_password')
                    ->placeholder(__('Bitte geben Sie Ihr aktuelles Passwort ein'))
                    ->required()
                    ->title(__('Altes Passwort'))
                    ->help('Ihr aktuelles Passwort.'),

                Password::make('password')
                    ->placeholder(__('Geben Sie Ihr neues Passwort ein'))
                    ->required()
                    ->title(__('Neues Passwort')),

                Password::make('password_confirmation')
                    ->placeholder(__('Geben Sie Ihr neues Passwort erneut ein'))
                    ->required()
                    ->title(__('Neues Passwort bestätigen'))
                    ->help('Ein gutes Passwort hat mindestens 8 Zeichen und besteht aus mindestens einer Zahl und einem Kleinbuchstaben.'),
            ]))
                ->title(__('Passwort ändern'))
                ->applyButton(__('Passwort ändern')),
        ];
    }

    /**
     * @param Request $request
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|password:web',
            'password'     => 'required|confirmed|min:8',
        ]);

        tap($request->user(), function ($user) use ($request) {
            $user->password = Hash::make($request->get('password'));
        })->save();

        Toast::info(__('Passwort erfolgreich geändert!'));
    }
}
