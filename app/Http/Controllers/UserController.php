<?php

namespace App\Http\Controllers;

use App\Models\PermissionCompany;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getusers()
    {
        $query = "SELECT
        users.*,
        roles.name 'role',
        (SELECT GROUP_CONCAT(CONCAT(com.name,'(',com.id,')')) FROM permission_company AS em
        INNER JOIN companies AS com ON em.company_id=com.id
        WHERE em.user_id=users.id) AS 'Empresa',
        DATE_FORMAT(users.created_at, '%m/%d/%Y %H:%i') AS 'createDate',
        DATE_FORMAT(users.updated_at, '%m/%d/%Y %H:%i') AS 'updateDate'
        FROM
        users
        LEFT JOIN model_has_roles AS rol ON users.id = rol.model_id
        LEFT JOIN roles ON rol.role_id = roles.id";

        $result['data'] = DB::select(DB::raw($query));
        return response()->json($result);
    }

    public function valmail($mail){
        $mailval = User::where('email', $mail)->exists();
        return response()->json($mailval);
    }

    public function getUserid($id)
    {
        $Users = "SELECT
        (SELECT GROUP_CONCAT(com.name) FROM permission_company AS em
        INNER JOIN companies AS com ON em.company_id=com.id
        WHERE em.user_id=users.id) AS 'CompaniesName',
        (SELECT GROUP_CONCAT(com.id) FROM permission_company AS em
        INNER JOIN companies AS com ON em.company_id=com.id
        WHERE em.user_id=users.id) AS 'CompaniesId',
        roles.name AS role,
        users.*
        FROM
        users
        INNER JOIN model_has_roles ON users.id = model_has_roles.model_id
        INNER JOIN roles ON model_has_roles.role_id = roles.id
        WHERE
        users.id = ".base64_decode($id)."";
        $result = DB::select(DB::raw($Users));
        return response()->json($result);
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

       if($request->hasFile("avatar")){
        $imagen = $request->file("avatar");
        $nombre =  time()."_".$imagen->getClientOriginalName();
        Storage::disk('avatar')->put($nombre,  File::get($imagen));
       }

       $user = new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->password = bcrypt($request->pass);
       $user->image = $nombre;
       $user->state = 'Active';
       $user->assignRole($request->role);
       $user->save();
       $permission = json_decode($request->permissioncompany, TRUE);
       foreach ($permission as $per){
            $percompany= new PermissionCompany();
            $percompany->user_id = $user['id'];
            $percompany->company_id = $per['value'];
            $percompany->state = 1;
            $percompany->save();
        }
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

       if($request->hasFile("avataredit")){
        $imagen = $request->file("avataredit");
        if($imagen->getClientOriginalName()!=$request->logoeditoriginal){
            $nombre =  time()."_".$imagen->getClientOriginalName();
        Storage::disk('avatar')->put($nombre,  File::get($imagen));
        }else{
            $nombre = $request->logoeditoriginal;
        }
       }else{
            $nombre = $request->logoeditoriginal;
       }
       $user = User::find($request->idedit);
       $user->name = $request->nameedit;
       $user->image = $nombre;
       $roles = $user->getRoleNames();
       //dd($roles);
       if(!$user->hasRole($request->roleedit)) {
        $user->removeRole($roles[0]);
        $user->assignRole($request->roleedit);}
       $user->save();
       $permission = json_decode($request->permissioncompanyedit, TRUE);
       //dd($permission);
       foreach ($permission as $per){
            $valperuser = PermissionCompany::where('user_id', '=',$user['id']);
            $valperuser -> delete();
            $percompany= new PermissionCompany();
            $percompany->user_id = $user['id'];
            $percompany->company_id = $per['value'];
            $percompany->state = 1;
            $percompany->save();
        }
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find(base64_decode($id));
        $user->delete();
        return response()->json(array(
            "res" => "1"
        ));
    }

    public function changedtatus($id, $status)
    {
        //dd(base64_decode($status));
        if(base64_decode($status)=='Active'){
            $estadofinal = 'Inactive';
        }else if(base64_decode($status)=='Inactive'){
            $estadofinal = 'Active';
        }
        $user = User::find(base64_decode($id));
        $user->state = $estadofinal;
        $user->save();
        return response()->json(array(
            "res" => "1"
        ));
    }
}
