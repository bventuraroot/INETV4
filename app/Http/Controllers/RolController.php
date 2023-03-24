<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rolesdata = Rol::all();
        $ban = 0;
        $permissionsbyrol = array();
        foreach($rolesdata as $index => $value){
            $Users = "SELECT
            b.name,
            b.image,
            b.email
            FROM model_has_roles a
            INNER JOIN users b ON a.model_id=b.id
            WHERE a.role_id=$value->id";
            $result = DB::select(DB::raw($Users));
            $rolesdata[$index]['userdata']=array();
            $rolesdata[$index]['countusers']=array();
            foreach($result as $index2 => $value2){
                $ban++;
            }
            $rolesdata[$index]['userdata']= $result;
            $rolesdata[$index]['countusers'] = $ban;
            $ban=0;
            $permissions ="SELECT
            b.name AS Rol,
            SUBSTRING_INDEX(c.name,'.',1) as modules,
            SUBSTRING_INDEX(c.name,'.',-1) as modulestrue
            FROM role_has_permissions a
            INNER JOIN roles b ON a.role_id=b.id
            INNER JOIN permissions c ON a.permission_id=c.id
            WHERE b.id=$value->id
            GROUP BY b.name,SUBSTRING_INDEX(c.name,'.',1),SUBSTRING_INDEX(c.name,'.',-1)";
            $result2 = DB::select(DB::raw($permissions));
            foreach($result2 as $index3 => $value3){
                @$permissionsbyrol[$value->name][$value3->modules][$value3->modulestrue]=$value3->modulestrue;
            }
        }
        $permissions = "SELECT
        SUBSTRING_INDEX(c.name,'.',1) as modules
        FROM permissions c
        GROUP BY SUBSTRING_INDEX(c.name,'.',1)";
        $permissions = DB::select(DB::raw($permissions));
        //dd($rolesdata);
        return view('admin.users.roles.index', array(
            "roles" => $rolesdata,
            "permissiondata" => $permissions,
            "permissionsbyrol" => $permissionsbyrol
        ));
    }

    public function getRoles()
    {
        $Roles = Rol::all();
        return response()->json($Roles);
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
        $role = Role::create(['name' => $request->modalRoleName]);
        $permisosactualizados = [];
        $permi = '';
        foreach($request as $index => $value){
            if($index=='request'){
                foreach($value as $index2 => $value2){
                    if($index2=='_token' || $index2=='_method' || $index2=='rolid' || $index2=='modalRoleName'){
                    }else{
                        $permi = str_replace('_', '.', $index2);
                        $permisosactualizados[] = $permi;
                    }
                }
            }
         }
         //dd($permisosactualizados);
         $role->syncPermissions($permisosactualizados);
         return redirect()->route('rol.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Rol $rol)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit(Rol $rol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rol $rol)
    {
        $role = Role::find($request->rolid);
        $permisosactualizados = [];
        $permi = '';
        foreach($request as $index => $value){
            if($index=='request'){
                foreach($value as $index2 => $value2){
                    if($index2=='_token' || $index2=='_method' || $index2=='rolid'){
                    }else{
                        $permi = str_replace('_', '.', $index2);
                        $permisosactualizados[] = $permi;
                    }
                }
            }
         }
         //dd($permisosactualizados);
         $role->syncPermissions($permisosactualizados);

         return redirect()->route('rol.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $rol)
    {
        //
    }
}
