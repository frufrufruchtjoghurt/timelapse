<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Address;
use App\Company;
use App\User;
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
   * Got to the company creator
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

    $company->save();

    return \redirect(\route('company.create'));
  }

  /**
    * Get all users from the database and show them
    *
    */
    public function show()
    {
      return \view('company.show', ['companies' => Company::all(), 'addresses' => Address::all()]);
    }

    /**
    * Delete selected company and (if unused) address from database
    *
    */
    public function destroy($id)
    {
      $company = Company::findOrFail($id);

      if (User::where('cid', $company->id)->exists())
      {
        return \redirect(\route('company.show'))->with('failure', 'Bitte zuerst alle Nutzer entfernen!');
      }
      else
      {
        $company_address = $company->aid;

        $company->delete();

        if (!Company::where('aid', $company_address)->exists())
        {
          $address = Address::findOrFail($company_address);
          $address->delete();
        }

        return \redirect(\route('company.show'))->with('success', 'Firma erfolgreich gelöscht!');

      }
    }
}
