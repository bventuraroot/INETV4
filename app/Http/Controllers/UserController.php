<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
