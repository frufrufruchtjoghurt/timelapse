<?php

namespace App\Http\Controllers;

use App\Models\PasswordResets;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Orchid\Support\Facades\Alert;

class PasswordController extends Controller
{
    public function storePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'confirmed|required',
            'password' => 'confirmed|required|min:8',
            'password_confirmation' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            Alert::error(__('Bitte prüfen Sie Ihre Eingabe erneut!'));
            return redirect($request->fullUrl() . '/' . $request->resetToken . '?email=' . urlencode($request->email));
        }

        try {
            $reset = PasswordResets::query()->where('email', '=', $request->email)
                ->where('token', '=', $request->resetToken)->firstOrFail();

            if (Carbon::now()->subtract('hours', 17)->isAfter($reset->created_at)) {
                $reset->delete();
                throw new Exception('Token invalid!');
            }
        } catch (Exception $e) {
            return redirect()->route('password.token');
        }

        try {
            $user = User::query()->where('email', '=', $request->email)->firstOrFail();

            $user->password = bcrypt($request->password);
            if ($user->email_verified_at == null) {
                $user->email_verified_at = Carbon::now();
            }

            $user->save();
        } catch (Exception $e) {
            return redirect()->route('password.nouser');
        }

        PasswordResets::query()->where('email', '=', $request->email)
            ->where('token', '=', $request->resetToken)->delete();

        return redirect()->route('platform.main');
    }
}
