<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Phone;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //dd(Client::where('company_id', base64_decode($company))->get());
         $providers = Provider::join('addresses', 'providers.address_id', '=', 'addresses.id')
         ->join('countries', 'addresses.country_id' , '=', 'countries.id')
         ->join('departments', 'addresses.department_id' , '=', 'departments.id')
         ->join('municipalities', 'addresses.municipality_id' , '=', 'municipalities.id')
         ->join('companies', 'providers.company_id' , '=', 'companies.id')
         ->join('phones', 'providers.phone_id', '=' ,'phones.id')
         ->select('providers.*', 'companies.name as company',
         'phones.phone as tel1',
         'phones.phone_fijo as tel2',
         'countries.name as pais', 'departments.name as departamento',
         'municipalities.name as municipio',
         'addresses.reference as address')->get();
            return view('providers.index', array(
                "providers" => $providers
            ));
    }

    public function getproviderid($id){
        $provider = Provider::join('addresses', 'providers.address_id', '=', 'addresses.id')
        ->join('countries', 'addresses.country_id' , '=', 'countries.id')
        ->join('departments', 'addresses.department_id' , '=', 'departments.id')
        ->join('municipalities', 'addresses.municipality_id' , '=', 'municipalities.id')
        ->join('companies', 'providers.company_id' , '=', 'companies.id')
        ->join('phones', 'providers.phone_id', '=' ,'phones.id')
        ->select('providers.*',
        'companies.name as company',
        'companies.id as companyid',
        'phones.phone as tel1',
         'phones.phone_fijo as tel2',
        'countries.name as pais',
        'countries.id as paisid',
        'departments.name as departamento',
        'departments.id as departamentoid',
        'municipalities.name as municipio',
        'municipalities.id as municipioid',
        'addresses.reference as address')
        ->where('providers.id', '=', base64_decode($id))
        ->get();
        return response()->json($provider);
    }

    public function getproviders(){
        $provider = Provider::all();
        return response()->json($provider);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $phone = new Phone();
        $phone->phone = $request->tel1;
        $phone->phone_fijo = $request->tel2;
        $phone->save();

        $address = new Address();
        $address->country_id = $request->country;
        $address->department_id = $request->departament;
        $address->municipality_id = $request->municipio;
        $address->reference = $request->address;
        $address->save();
        //dd($request);
        $provider = new Provider();
        $provider->razonsocial = $request->razonsocial;
        $provider->ncr = $request->ncr;
        $provider->nit = $request->nit;
        $provider->email = $request->email;
        $provider->company_id = $request->company;
        $provider->address_id = $address['id'];
        $provider->phone_id = $phone['id'];
        $provider->save();
        return redirect()->route('provider.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(Provider $provider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provider $provider)
    {
        //dd($request);
        $phone = Phone::find($request->phone_idupdate);
        $phone->phone = $request->tel1update;
        $phone->phone_fijo = $request->tel2update;
        $phone->save();

        $address = Address::find($request->address_idupdate);
        $address->country_id = $request->countryedit;
        $address->department_id = $request->departamentedit;
        $address->municipality_id = $request->municipioedit;
        $address->reference = $request->addressupdate;
        $address->save();
        //dd($request);
        $provider = Provider::find($request->idupdate);
        $provider->razonsocial = $request->razonsocialupdate;
        $provider->ncr = $request->ncrupdate;
        $provider->nit = $request->nitupdate;
        $provider->email = $request->emailupdate;
        $provider->company_id = $request->companyupdate;
        $provider->address_id = $address['id'];
        $provider->phone_id = $phone['id'];
        $provider->save();
        return redirect()->route('provider.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd($id);
        $Provider = Provider::find(base64_decode($id));
        $Provider->delete();
        return response()->json(array(
            "res" => "1"
        ));
    }
}
