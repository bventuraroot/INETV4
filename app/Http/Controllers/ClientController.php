<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Client;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Arrays;

use function PHPUnit\Framework\isNull;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company="0")
    {
        //dd(Client::where('company_id', base64_decode($company))->get());
        if($company!=0){
            return view('client.index', array(
                "clients" => Client::where('company_id', base64_decode($company))->get(),
                "companyselected" =>base64_decode($company)
            ));
        }else{
            return view('client.index');
        }

    }

    public function getclientbycompany($idcompany){
        $query="SELECT
        a.*,
       (CASE a.tpersona WHEN 'N' THEN CONCAT(a.firstname , ' ', a.firstlastname) WHEN 'J' THEN a.comercial_name END) AS name_format_label
       FROM clients a WHERE a.company_id=".  base64_decode($idcompany) ."";
        //$clients = Client::join('companies', 'clients.company_id', '=', 'companies.id')
        //->select('clients.id',
        //        'clients.firstname',
        //        'clients.secondname')
        //->where('companies.id', '=', base64_decode($idcompany))
        //->get();
        $result = DB::select(DB::raw($query));
        return response()->json($result);
    }

    public function gettypecontri($client){
        $contribuyente = Client::find(base64_decode($client));
        return response()->json($contribuyente);
    }

    public function getClientid($id)
    {
        $Client = Client::join('addresses', 'clients.address_id', '=', 'addresses.id')
        ->join('countries', 'addresses.country_id' , '=', 'countries.id')
        ->join('departments', 'addresses.department_id' , '=', 'departments.id')
        ->join('municipalities', 'addresses.municipality_id' , '=', 'municipalities.id')
        ->join('economicactivities', 'clients.economicactivity_id' , '=', 'economicactivities.id')
        ->join('phones', 'clients.phone_id' , '=', 'phones.id')
        ->select('clients.*',
        'countries.name as pais',
        'departments.name as departamento',
        'municipalities.name as municipio',
        'economicactivities.name as econo',
        'addresses.reference as address',
        'phones.phone',
        'phones.phone_fijo',
        'addresses.country_id as country',
        'addresses.department_id as departament',
        'addresses.municipality_id as municipio',
        'clients.economicactivity_id as acteconomica')
        ->where('clients.id', '=', base64_decode($id))
        ->get();
        return response()->json($Client);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('client.create');
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
        $client = new Client();
        $client->firstname = (is_null($request->firstname)? 'N/A': $request->firstname);
        $client->secondname = (is_null($request->firstname)? 'N/A': $request->secondname);
        $client->firstlastname = (is_null($request->firstlastname)? 'N/A': $request->firstlastname);
        $client->secondlastname = (is_null($request->secondlastname)? 'N/A': $request->secondlastname);
        $client->comercial_name = (is_null($request->comercial_name)? 'N/A': $request->comercial_name);
        $client->email = $request->email;
        if($request->contribuyente=='on'){
            $contri='1';
        }else{
            $contri='0';
        }
        $client->ncr = (is_null($request->ncr)? 'N/A': $request->ncr);
        $client->giro = (is_null($request->giro)? 'N/A': $request->giro);
        $client->nit = $request->nit;
        $client->legal = (is_null($request->legal)? 'N/A': $request->legal);
        $client->tpersona = $request->tpersona;
        $client->contribuyente = $contri;
        $client->tipoContribuyente = $request->tipocontribuyente;
        $client->economicactivity_id = $request->acteconomica;
        $client->birthday = date('Ymd', strtotime($request->birthday));
        $client->empresa = (is_null($request->empresa)? 'N/A': $request->empresa);
        $client->company_id = $request->companyselected;
        $client->address_id = $address['id'];
        $client->phone_id = $phone['id'];
        $client->save();
        $com=$request->companyselected;
        return redirect()->route('client.index',base64_encode($com));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('Company.view', array(
            "company" => Client::join('addresses', 'companies.address_id', '=', 'addresses.id')
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
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //return view('client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {

        $phone = Phone::find($request->phoneeditid);
        $phone->phone = $request->tel1edit;
        $phone->phone_fijo = $request->tel2edit;
        $phone->save();

        $address = Address::find($request->addresseditid);
        $address->country_id = $request->countryedit;
        $address->department_id = $request->departamentedit;
        $address->municipality_id = $request->municipioedit;
        $address->reference = $request->addressedit;
        $address->save();
        //dd($request);
        $client = Client::find($request->idedit);
        $client->firstname = $request->firstnameedit;
        $client->secondname = $request->secondnameedit;
        $client->firtslastname = $request->firtslastnameedit;
        $client->secondlastname = $request->secondlastnameedit;
        $client->comercial_name = $request->comercial_nameedit;
        $client->email = $request->emailedit;
        $client->ncr = $request->ncredit;
        $client->giro = $request->giroedit;
        $client->nit = $request->nitedit;
        $client->legal = $request->legaledit;
        $client->tpersona = $request->tpersonaedit;
        $client->contribuyente = $request->contribuyenteeditvalor;
        $client->tipoContribuyente = $request->tipocontribuyenteedit;
        $client->economicactivity_id = $request->acteconomicaedit;
        $client->birthday = date('Ymd', strtotime($request->birthdayedit));
        $client->empresa = $request->empresaedit;
        $client->address_id = $address['id'];
        $client->phone_id = $phone['id'];
        $client->save();
        $com=$request->companyselectededit;
        return redirect()->route('client.index',base64_encode($com));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //dd($id);
         $Client = Client::find(base64_decode($id));
         $Client->delete();
         return response()->json(array(
             "res" => "1"
         ));
    }
}
