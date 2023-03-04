<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.permissions.index');
    }

    public function getpermission()
    {
        $permission['data'] = Permission::all();
        //dd($permission['data']);
        foreach($permission['data'] as $index => $value){
                @$permission['data'][$index]['roles']=[];
                $idpermissiondata = @$value->id;
                $roles = "SELECT b.name AS rolename FROM role_has_permissions a
                INNER JOIN roles b ON a.role_id=b.id
                WHERE a.permission_id='$idpermissiondata'
                GROUP BY b.name";
                $result = DB::select(DB::raw($roles));
                foreach($result as $index2 => $value2){
                    @$permission['data'][$index]['roles']=$result;
                }
        }
        //dd($permission['data']);
        return response()->json($permission);
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
        Permission::create(['name' => $request->modalPermissionName]);
        return redirect()->route('permission.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $permission = Permission::find($request->editPermissionid);
        $permission->name = $request->editPermissionName;
        $permission->save();
        return redirect()->route('permission.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission = Permission::find(base64_decode($id));
        $permission->delete();
        return response()->json(array(
            "res" => "1"
        ));
    }
}
