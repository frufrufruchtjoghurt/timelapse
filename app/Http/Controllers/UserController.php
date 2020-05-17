<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      return view('user.index');
    }

    /**
     * Got to the user creator
     *
     *  @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
      return view('user.create', ['companies' => Company::all()]);
    }

    /**
     * Handle a registration request for the application.
     *
     */
    public function store()
    {
        \error_log("Hello!");

        $user = new User();

        $user->title = \request('title');
        $user->gender = \request('gender');
        $user->first_name = \request('first_name');
        $user->last_name = \request('last_name');
        $user->email = \request('email');

        $role = \request('role');
        if ($role)
        {
          $user->role = $role;
        }

        $password = Faker::create()->password(12, 12);
        \error_log($password);
        $user->password = Hash::make($password);

        $user->save();

        \event(new Registered($user));

        return redirect(\route('user.create'));
    }

    /**
    * Get all users from the database and show them
    *
    */
    public function show()
    {
      return \view('user.show', ['users' => User::all()]);
    }

    /**
    * Delete selected user from database
    *
    */
    public function destroy($id)
    {
      $user = User::findOrFail($id);
      $user->delete();

      return \redirect(\route('user.show'))->with('success', 'Nutzer erfolgreich entfernt!');
    }
}
