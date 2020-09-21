<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Company;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{

  /**
   * Show company details.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    if (Auth::user()->cid)
    {
      $company = Company::find(Auth::user()->cid);
      $address = Address::find($company->aid);
    }
    else {
      $company = NULL;
      $address = NULL;
    }
    return view('company.index', ['company' => $company, 'address' => $address]);
  }

  /**
   * Go to the company creator
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('company.create');
  }

  /**
   *  Store a new Company with address
   */
  public function store()
  {
    $address = new Address();

    $address->street = request('street');
    $address->street_nr = request('street_nr');
    $address->staircase = request('staircase');
    $address->door_nr = request('door_nr');
    $address->postcode = request('postcode');
    $address->city = request('city');
    $address->country = request('country');

    $first_match = Address::where([
      ['street', $address->street],
      ['street_nr', $address->street_nr],
      ['staircase', $address->staircase],
      ['door_nr', $address->door_nr],
      ['postcode', $address->postcode],
      ['city', $address->city],
      ['country', $address->country],
    ]);

    $company = new Company();

    if ($first_match->exists())
    {
      $company->aid = $first_match->pluck('id')->first();
    }
    else {
      $address->save();
      $company->aid = Address::latest('created_at')->pluck('id')->first();
    }

    $company->name = \request('name');

    if (Company::where([['name', $company->name], ['aid', $company->aid]])->exists())
    {
      return \redirect(\route('company.create'))->with('error', 'Firma bereits angelegt!');
    }

    $company->save();

    return \redirect(\route('company.create'))->with('success', 'Firma erfolgreich angelegt!');
  }

  /**
    * Get all users from the database and show them
    *
    */
    public function list()
    {
      return \view('company.list', ['companies' => Company::all(), 'addresses' => Address::all()]);
    }

    /**
    * Show details of selected company
    *
    */
    public function show($id)
    {
      try
      {
        $company = Company::findOrFail($id);
        $address = Address::findOrFail($company->aid);
      }
      catch(Exception $e)
      {
        \error_log($e->getMessage());
        return \redirect(route('company.list'))->with('error', 'Firmen-ID oder Addresse nicht gefunden!');
      }

      return \view('company.show', ['company' => $company, 'address' => $address]);
    }

    /**
    * Edit details of selected company
    *
    */
    public function edit($id)
    {
      try
      {
        $company = Company::findOrFail($id);
        $address = Address::findOrFail($company->aid);
      }
      catch(Exception $e)
      {
        \error_log($e->getMessage());
        return \redirect(route('user.list'))->with('error', 'Firmen-ID nicht gefunden!');
      }

      return \view('company.edit', ['company' => $company, 'address' => $address]);
    }

    /**
     * Save changed data of the company.
     *
     */
    public function save($id)
    {
      try
        {
          $old_address = Company::where('id', $id)->pluck('aid')->first();
          $aid = NULL;
          $address = new Address();

          $address->street = request('street');
          $address->street_nr = request('street_nr');
          $address->staircase = request('staircase');
          $address->door_nr = request('door_nr');
          $address->postcode = request('postcode');
          $address->city = request('city');
          $address->country = request('country');

          $first_match = Address::where([
            ['street', $address->street],
            ['street_nr', $address->street_nr],
            ['staircase', $address->staircase],
            ['door_nr', $address->door_nr],
            ['postcode', $address->postcode],
            ['city', $address->city],
            ['country', $address->country],
          ]);

          if ($first_match->exists())
          {
            $aid = $first_match->pluck('id')->first();
          }
          else
          {
            $address->save();
            $aid = Address::latest('created_at')->pluck('id')->first();
          }

          Company::where('id', $id)->update([
            'name' => \request('name'),
            'aid' => $aid
          ]);

          if ($aid != $old_address && !Company::where('aid', $old_address)->exists())
          {
            try
            {
              $address = Address::findOrFail($old_address);
              $address->delete();
            }
            catch (Exception $e)
            {
              \error_log($e->getMessage());
              return \redirect(\route('company.list'))->with('error', 'Es ist beim entfernen einer Adresse ein Fehler aufgetreten!');
            }
          }

          return redirect(\route('company.list'))->with('success', 'Firma erfolgreich bearbeitet!');
        }
        catch(Exception $e)
        {
          \error_log($e->getMessage());
          return \redirect(route('company.list'))->with('error', 'Bearbeitungsfehler!');
        }
    }

    /**
    * Delete selected company and (if unused) address from database
    *
    */
    public function destroy($id)
    {
      if (\request('delete') !== 'LÖSCHEN')
      {
        return \redirect(\route('company.show', [$id]))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
      }
      try
      {
        $company = Company::findOrFail($id);

        if (User::where('cid', $company->id)->exists())
        {
          return \redirect(\route('company.show' [$id]))->with('error', 'Bitte zuerst alle Nutzer entfernen!');
        }
        else
        {
          $company_address = $company->aid;

          $company->delete();

          if (!Company::where('aid', $company_address)->exists())
          {
            try
            {
              $address = Address::findOrFail($company_address);
              $address->delete();
            }
            catch (Exception $e)
            {
              \error_log($e->getMessage());
              return \redirect(\route('company.list'))->with('error', 'Es ist beim entfernen einer Adresse ein Fehler aufgetreten!');
            }
          }

          return \redirect(\route('company.list'))->with('success', 'Firma erfolgreich gelöscht!');

        }
      }
      catch(Exception $e)
      {
        \error_log($e->getMessage());
        return \redirect(\route('company.list'))->with('error', 'Es ist ein Fehler aufgetreten!');
      }
    }
}
