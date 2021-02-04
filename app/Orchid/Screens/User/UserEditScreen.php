<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPermissionLayout;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Access\UserSwitch;
//use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;
use phpDocumentor\Reflection\Types\This;

class UserEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Kunde erstellen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Details, wie Name, E-Mail-Adresse und Passwort';

    /**
     * @var string
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
     * @param User $user
     *
     * @return array
     */
    public function query(User $user): array
    {
        $this->exists = $user->exists;
        $phone_nr = ['', '', ''];

        if ($this->exists)
        {
            $this->name = 'Kunde bearbeiten';
            $phone_nr = explode("/", $user->phone_nr);
        }

        $user->load(['roles']);

        return [
            'user'       => $user,
            'permission' => $user->getStatusPermission(),
            'phone_country_code' => $phone_nr[0],
            'phone_nr' => $phone_nr[1],
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
            Button::make(__('Als dieser Kunde anmelden'))
                ->icon('login')
                ->method('loginAs')
                ->canSee($this->exists),

            Button::make(__('Änderungen speichern'))
                ->icon('check')
                ->method('save')
                ->canSee($this->exists),

            Button::make(__('Kunde erstellen'))
                ->icon('pencil')
                ->method('save')
                ->canSee(!$this->exists),

            Button::make(__('Löschen'))
                ->icon('trash')
                ->confirm('Möchten Sie diesen Kunden wirklich löschen?')
                ->method('remove')
                ->canSee($this->exists),
        ];
    }

    /**
     * @return Layout[]
     */
    public function layout(): array
    {
        if (Auth::user()->hasAccess('admin'))
        {
            return [
                new UserEditLayout(false, true, $this->exists),
                UserPermissionLayout::class,
            ];
        }
        return [
            new UserEditLayout(false, true),
        ];
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(User $user, Request $request)
    {
        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
        ]);

        $permissions = collect($request->get('permissions'))
            ->map(function ($value, $key) {
                return [base64_decode($key) => $value];
            })
            ->collapse()
            ->toArray();

        array_push($permissions, ['platform.index' => true]);

        if (!$this->exists)
        {
            $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
            $password = substr($random, 0, 12);
            $user->password = Hash::make($password);
        }

        if ($request->get('phone_country_code') || $request->get('phone_nr')) {
            $request->validate([
                'phone_country_code' => 'required',
                'phone_nr' => 'required',
            ]);
            $user->phone_nr = $request->get('phone_country_code') . '/' . $request->get('phone_nr');
        }

        $user->fill($request->get('user'))
            ->replaceRoles($request->input('user.roles'))
            ->fill([
                'permissions' => $permissions,
            ])
            ->save();

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.users');
    }

    /**
     * @param User $user
     *
     * @throws Exception
     *
     * @return RedirectResponse
     */
    public function remove(User $user)
    {
        $user->delete();

        Toast::info(__('User was removed'));

        return redirect()->route('platform.users');
    }

    /**
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function loginAs(User $user)
    {
        UserSwitch::loginAs($user);

        return redirect()->route(config('platform.index'));
    }
}
