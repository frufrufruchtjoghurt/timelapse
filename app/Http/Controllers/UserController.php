<?php

namespace App\Http\Controllers;

use App\Company;
use App\User;
use App\Role;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

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
        $user = new User();

        $user->title = \request('title');
        $user->gender = \request('gender');
        $user->first_name = \request('first_name');
        $user->last_name = \request('last_name');
        $user->email = \request('email');

        if (User::where('email', $user->email)->exists())
        {
          return redirect(\route('user.create'))->with('error', 'E-Mail bereits registriert!');
        }

        $role = \request('role');
        if ($role)
        {
          $user->rid = Role::where('name', $role)->pluck('id')->first();
        }

        $user->cid = \request('cid');

        $password = Faker::create()->password(12, 12);
        \error_log($password);
        $user->password = Hash::make($password);

        $user->save();

        \event(new Registered($user));

        return redirect(\route('user.create'))->with('success', 'Benutzer erfolgreich registriert!');
    }

    /**
    * Get all users from the database and show them
    *
    */
    public function list()
    {
      return \view('user.list', ['users' => User::all(), 'roles' => Role::all()]);
    }

    /**
    * Show details of selected user
    *
    */
    public function show($id)
    {
      try
      {
        $user = User::findOrFail($id);
        $role = Role::findOrFail($user->rid);
        $company = Company::findOrFail($user->cid);
      }
      catch(Exception $e)
      {
        \error_log($e->getMessage());
        return \redirect(route('user.list'))->with('error', 'Nutzer-ID, Firmendaten oder Nutzerrechte nicht gefunden!');
      }

      return \view('user.show', ['user' => $user, 'role' => $role, 'company' => $company]);
    }

    /**
    * Edit details of selected user
    *
    */
    public function edit($id)
    {
      try
      {
        $user = User::findOrFail($id);
      }
      catch(Exception $e)
      {
        \error_log($e->getMessage());
        return \redirect(route('user.list'))->with('error', 'Nutzer-ID nicht gefunden!');
      }

      return \view('user.edit', ['user' => $user, 'roles' => Role::all(), 'companies' => Company::all()]);
    }

    /**
     * Save changed data of the user.
     *
     */
    public function save($id)
    {
      if ($id == Auth::user()->id)
      {
        try
        {
          User::where('id', $id)->update('email', request('email'));
          return redirect(\route('user.index'))->with('success', 'Benutzer erfolgreich bearbeitet!');
        }
        catch(Exception $e)
        {
          \error_log($e->getMessage());
          return \redirect(route('user.index'))->with('error', 'Bearbeitungsfehler!');
        }
      }
      else
      {
        try
        {
          User::where('id', $id)->update([
            'title' => \request('title'),
            'gender' => \request('gender'),
            'first_name' => \request('first_name'),
            'last_name' => \request('last_name'),
            'email' => \request('email'),
            'rid' => Role::where('name', \request('role'))->pluck('id')->first(),
            'cid' => \request('cid')
            ]);
          return redirect(\route('user.list'))->with('success', 'Benutzer erfolgreich bearbeitet!');
        }
        catch(Exception $e)
        {
          \error_log($e->getMessage());
          return \redirect(route('user.list'))->with('error', 'Bearbeitungsfehler!');
        }
      }
    }

    /**
    * Delete selected user from database
    *
    */
    public function destroy($id)
    {
      if (\request('delete') !== 'LÖSCHEN')
      {
        return \redirect(\route('user.show', [$id]))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
      }
      try
      {
        $user = User::findOrFail($id);
        $user->delete();
      }
      catch(Exception $e)
      {
        \error_log($e->getMessage());
        return \redirect(\route('user.list'))->with('error', 'Es ist ein Fehler aufgetreten!');
      }


      return \redirect(\route('user.list'))->with('success', 'Nutzer erfolgreich entfernt!');
    }
}
