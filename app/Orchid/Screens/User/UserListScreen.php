<?php

namespace App\Orchid\Screens\User;

use App\Mail\PasswordNew;
use App\Mail\PasswordReset;
use App\Models\PasswordResets;
use App\Models\User;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserFiltersLayout;
use App\Orchid\Layouts\User\UserListLayout;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
//use Orchid\Platform\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Kunden';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Alle registrierten Kunden';

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
            'users' => User::filters()
                ->filtersApplySelection(UserFiltersLayout::class)
                ->defaultSort('last_name')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('Erstellen'))
                ->icon('pencil')
                ->route('platform.users.edit')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            UserFiltersLayout::class,
            UserListLayout::class,

            Layout::modal('oneAsyncModal', UserEditLayout::class)
                ->async('asyncGetUser'),
        ];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function asyncGetUser(User $user): array
    {
        $phone_nr = explode('/', $user->phone_nr);

        return [
            'user' => $user,
            'phone_country_code' => $phone_nr[0],
            'phone_nr' => $phone_nr[1],
        ];
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUser(User $user, Request $request)
    {
        $request->validate([
            'user.email' => 'required|unique:users,email,'.$user->id,
        ]);

        $user->fill($request->input('user'))
            ->replaceRoles($request->input('user.roles'))
            ->save();

        $user->phone_nr = $request->get('phone_country_code') . $request->get('phone_code')
            . $request->get('phone_nr');

        Toast::info(__('User was saved.'));
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request)
    {
        User::findOrFail($request->get('id'))
            ->delete();

        Toast::info(__('Benutzer wurde gelöscht!'));
    }

    /**
     * @param Request $request
     */
    public function verifyPasswordRequest(Request $request) {
        $user = User::query()->where('id', '=', $request->get('id'))->firstOrFail();

        PasswordResets::query()->where('email', '=', $user->email)->delete();

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Str::random(50),
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')->where('email', '=', $user->email)->latest()->get()->first();

        if ($this->sendResetEmail($user, $tokenData->token)) {
            Toast::info(__('Link zum Zurücksetzen des Passworts von ' . $user->last_name . ' ' . $user->first_name
                . ' wurde an ' . $user->email . ' versandt!'));
        } else {
            Toast::error('Ein Netzwerkfehler ist aufgetreten!');
        }
    }

    private function sendResetEmail(User $user, string $token): bool
    {
        $link = URL::to('/') . '/reset/' . $token . "?email=" . urlencode($user->email);

        try {
            if ($user->email_verified_at == null) {
                Mail::to($user->email)->send(new PasswordNew($link));
            } else {
                Mail::to($user->email)->send(new PasswordReset($link));
            }
            return true;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }
}
