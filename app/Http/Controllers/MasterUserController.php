<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class MasterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')
                ->leftjoin('roles','roles.id','=','users.id_role')
                ->select('users.*','roles.role')
                ->get();
       $roles = DB::table('roles')->get();
        return view('users.index',compact('users','roles'));
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // validasi email unik
            'id_role' => 'required|exists:roles,id',
            'password' => 'required|min:6' // tambahin minimal password juga
        ]);

        // Hash password dan simpan ke array
        $validated['password'] = Hash::make($validated['password']);
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        // Simpan ke tabel users
        DB::table('users')->insert($validated);

        // Redirect atau response sukses
        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
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
        $idBs = base64_decode($id);
        $data = DB::table('users')->find($idBs);
        $roles = DB::table('roles')->get();
        return view('users.edit',compact('data','roles'));
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
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // unik kecuali untuk user ini
            'id_role' => 'required|exists:roles,id',
            'password' => 'nullable|min:6' // password opsional
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'id_role' => $validated['id_role'],
            'updated_at' => now(),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('users')->where('id',$id)->delete();
        return redirect()->back()->with('success','Menghapus User !');
    }
}
