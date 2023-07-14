<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configs = Config::join('companies AS co', 'config.company_id', '=', 'co.id')
        ->select('config.*','co.name AS name_company')
        ->get();

        return view('dtemh.config',array(
            'configs'=> $configs
        ));
    }

    public function getconfigid($id){
        $config = Config::join('companies AS co', 'config.company_id', '=', 'co.id')
        ->select('config.*','co.name AS name_company')
        ->where('config.id', '=', base64_decode($id))
        ->get();
        return response()->json($config);
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
        $config = new Config();
        $config->company_id = $request->company;
        $config->version = $request->version;
        $config->ambiente = $request->ambiente;
        $config->typeModel = $request->typemodel;
        $config->typeTransmission = $request->typetransmission;
        $config->typeContingencia = $request->typecontingencia;
        $config->versionJson = $request->versionjson;
        $config->passPrivateKey = $request->passprivatekey;
        $config->passkeyPublic = $request->passpublickey;
        $config->passMH = $request->passmh;
        $config->codeCountry = "9300";
        $config->nameCountry = "EL SALVADOR";
        $config->save();
        return redirect()->route('config.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function show(Config $config)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function edit(Config $config)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Config $config)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $config = Config::find(base64_decode($id));
        $config->delete();
        return response()->json(array(
             "res" => "1"
         ));
    }
}
