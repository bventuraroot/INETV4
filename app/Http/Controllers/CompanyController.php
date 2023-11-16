<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Company;
use App\Models\Phone;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company.index', array(
            "companies" => Company::join('addresses', 'companies.address_id', '=', 'addresses.id')
            ->join('countries', 'addresses.country_id' , '=', 'countries.id')
            ->join('departments', 'addresses.department_id' , '=', 'departments.id')
            ->join('municipalities', 'addresses.municipality_id' , '=', 'municipalities.id')
            ->join('economicactivities', 'companies.economicactivity_id' , '=', 'economicactivities.id')
            ->select('companies.*', 'countries.name as pais', 'departments.name as departamento', 'municipalities.name as municipio', 'economicactivities.name as econo', 'addresses.reference as address')->get()
        ));
    }

    public function getCompany()
    {
        $Company = Company::all();
        return response()->json($Company);
    }

    public function getCompanytag()
    {
        $Company = Company::select('companies.id as value', 'companies.name as name', 'companies.logo as avatar', 'companies.email as email')->get();
        return response()->json($Company);
    }

    public function getCompanybyuser($idUser)
    {
        $Company = Company::join('permission_company', 'companies.id', '=', 'permission_company.company_id')
        ->select('companies.id', 'companies.name', 'companies.tipoContribuyente')
        ->where('permission_company.user_id', '=', $idUser)
        ->get();
        return response()->json($Company);
    }

    public function gettypecontri($company){
        $contribuyente = Company::find(base64_decode($company));
        return response()->json($contribuyente);
    }

    public function getCompanyid($id)
    {
        $Company = Company::join('addresses', 'companies.address_id', '=', 'addresses.id')
        ->join('countries', 'addresses.country_id' , '=', 'countries.id')
        ->join('departments', 'addresses.department_id' , '=', 'departments.id')
        ->join('municipalities', 'addresses.municipality_id' , '=', 'municipalities.id')
        ->join('economicactivities', 'companies.economicactivity_id' , '=', 'economicactivities.id')
        ->join('phones', 'companies.phone_id' , '=', 'phones.id')
        ->select('companies.*',
        'countries.name as pais',
        'departments.name as departamento',
        'municipalities.name as municipio',
        'economicactivities.name as econo',
        'addresses.reference as address',
        'phones.phone',
        'addresses.country_id as country',
        'addresses.department_id as departament',
        'addresses.municipality_id as municipio',
        'companies.economicactivity_id as acteconomica')
        ->where('companies.id', '=', base64_decode($id))
        ->get();
        return response()->json($Company);
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
       //dd($request);

       $phone = new Phone();
       $phone->phone = $request->phone;
       $phone->save();

       $address = new Address();
       $address->country_id = $request->country;
       $address->department_id = $request->departament;
       $address->municipality_id = $request->municipio;
       $address->reference = $request->address;
       $address->save();

       if($request->hasFile("logo")){
        $imagen = $request->file("logo");
        $nombre =  time()."_".$imagen->getClientOriginalName();
        Storage::disk('logo')->put($nombre,  File::get($imagen));
       }
       //dd();
       $company = new Company();
       $company->name = $request->name;
       $company->email = $request->email;
       $company->nit = $request->nit;
       $company->ncr = $request->ncr;
       $company->giro = $request->giro;
       $company->cuenta_no = $request->cuenta_no;
       $company->tipoContribuyente = $request->tipocontribuyente;
       $company->tipoEstablecimiento = $request->tipoEstablecimiento;
       $company->address_id = $address['id'];
       $company->economicactivity_id = $request->acteconomica;
       $company->phone_id = $phone['id'];
       $company->logo = $nombre;
       $company->save();
       return redirect()->route('company.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $Company
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('company.view', array(
            "company" => Company::join('addresses', 'companies.address_id', '=', 'addresses.id')
            ->join('countries', 'addresses.country_id' , '=', 'countries.id')
            ->join('departments', 'addresses.department_id' , '=', 'departments.id')
            ->join('municipalities', 'addresses.municipality_id' , '=', 'municipalities.id')
            ->join('economicactivities', 'companies.economicactivity_id' , '=', 'economicactivities.id')
            ->select('companies.*', 'countries.name as pais', 'departments.name as departamento', 'municipalities.name as municipio', 'economicactivities.name as econo', 'addresses.reference as address')
            ->where('companies.id', '=', $id)
            ->get()
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $Company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $Company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $Company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $phone = Phone::find($request->phoneeditid);
        $phone->phone = $request->phoneedit;
        $phone->save();

        $address = Address::find($request->addresseditid);
        $address->country_id = $request->countryedit;
        $address->department_id = $request->departamentedit;
        $address->municipality_id = $request->municipioedit;
        $address->reference = $request->addressedit;
        $address->save();

        if($request->hasFile("logoedit")){
            $imagen = $request->file("logoedit");
            if($imagen->getClientOriginalName()!=$request->logoeditoriginal){
                $nombre =  time()."_".$imagen->getClientOriginalName();
            Storage::disk('logo')->put($nombre,  File::get($imagen));
            }else{
                $nombre = $request->logoeditoriginal;
            }
           }else{
                $nombre = $request->logoeditoriginal;
           }
           //dd($request);
        $Company = Company::find($request->idedit);
        $Company->name = $request->nameedit;
        $Company->email = $request->emailedit;
        $Company->nit = $request->nitedit;
        $Company->ncr = $request->ncredit;
        $Company->giro = $request->giroedit;
        $Company->cuenta_no = $request->cuenta_noedit;
        $Company->tipoContribuyente = $request->tipocontribuyenteedit;
        $Company->tipoEstablecimiento = $request->tipoEstablecimientoedit;
        $Company->address_id = $request->addresseditid;
        $Company->economicactivity_id = $request->acteconomicaedit;
        $Company->phone_id = $request->phoneeditid;
        $Company->logo = $nombre;
        $Company->save();
        return redirect()->route('company.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $Company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd($id);
        $Company = Company::find(base64_decode($id));
        $Company->delete();
        return response()->json(array(
            "res" => "1"
        ));
    }
}
