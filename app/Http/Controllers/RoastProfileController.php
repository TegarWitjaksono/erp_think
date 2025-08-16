<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RoastProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = DB::table('roast_profile')->get();

        return view('roastprofile.index',compact('items'));
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
        'deskripsi' => 'required|string',
        'charge_temp' => 'required|numeric',
        'charge_time_sec' => 'required|numeric',
        'tp_temp' => 'required|numeric',
        'tp_time_sec' => 'required|numeric',
        'de_temp' => 'required|numeric',
        'de_time_sec' => 'required|numeric',
        'fc_temp' => 'required|numeric',
        'fc_time_sec' => 'required|numeric',
        'sc_temp' => 'required|numeric',
        'sc_time_sec' => 'required|numeric',
        'drop_temp' => 'required|numeric',
        'drop_time_sec' => 'required|numeric',
    ]);

    DB::table('roast_profile')->insert([
        'deskripsi' => $validated['deskripsi'],
        'charge_temp' => $validated['charge_temp'],
        'charge_time_sec' => $validated['charge_time_sec'],
        'tp_temp' => $validated['tp_temp'],
        'tp_time_sec' => $validated['tp_time_sec'],
        'de_temp' => $validated['de_temp'],
        'de_time_sec' => $validated['de_time_sec'],
        'fcs_temp' => $validated['fc_temp'],
        'fcs_time_sec' => $validated['fc_time_sec'],
        'sc_temp' => $validated['sc_temp'],
        'sc_time_sec' => $validated['sc_time_sec'],
        'drop_temp' => $validated['drop_temp'],
        'drop_time_sec' => $validated['drop_time_sec'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('roast_profile.index')->with('success', 'Menambahkan Roast Profile!');
}


public function show($id)
{
    try {
        // Cari data roast profile berdasarkan ID
        $profile = DB::table('roast_profile')->find($id);

        // Return data dalam format JSON untuk AJAX request
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $profile->id,
                'deskripsi' => $profile->deskripsi,
                'charge_temp' => $profile->charge_temp,
                'charge_time_sec' => $profile->charge_time_sec ?? 0,
                'tp_temp' => $profile->tp_temp,
                'tp_time_sec' => $profile->tp_time_sec,
                'de_temp' => $profile->de_temp,
                'de_time_sec' => $profile->de_time_sec,
                'fc_temp' => $profile->fc_temp ?? $profile->fcs_temp, // fallback jika ada field lama
                'fc_time_sec' => $profile->fc_time_sec ?? $profile->fcs_time_sec,
                'sc_temp' => $profile->sc_temp,
                'sc_time_sec' => $profile->sc_time_sec,
                'drop_temp' => $profile->drop_temp,
                'drop_time_sec' => $profile->drop_time_sec,
                'created_at' => $profile->created_at,
                'updated_at' => $profile->updated_at,
            ]);
        }

        // Jika bukan AJAX request, return view (optional)
        return view('roast_profile.show', compact('profile'));

    } catch (\Exception $e) {
        // Handle error
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'error' => 'Profile tidak ditemukan',
                'message' => $e->getMessage()
            ], 404);
        }

        // Redirect dengan error message untuk non-AJAX
        return redirect()->back()->with('error', 'Profile tidak ditemukan');
    }
}

    // Method tambahan untuk mendukung berbagai use case
    public function getProfileData($id)
    {
        try {
            $profile = RoastProfile::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $profile->id,
                    'deskripsi' => $profile->deskripsi,
                    'charge_temp' => (float) $profile->charge_temp,
                    'charge_time_sec' => (int) ($profile->charge_time_sec ?? 0),
                    'tp_temp' => (float) $profile->tp_temp,
                    'tp_time_sec' => (int) $profile->tp_time_sec,
                    'de_temp' => (float) $profile->de_temp,
                    'de_time_sec' => (int) $profile->de_time_sec,
                    'fc_temp' => (float) ($profile->fc_temp ?? $profile->fcs_temp),
                    'fc_time_sec' => (int) ($profile->fc_time_sec ?? $profile->fcs_time_sec),
                    'sc_temp' => (float) $profile->sc_temp,
                    'sc_time_sec' => (int) $profile->sc_time_sec,
                    'drop_temp' => (float) $profile->drop_temp,
                    'drop_time_sec' => (int) $profile->drop_time_sec,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $idEn = base64_decode($id);
        $data = DB::table('roast_profile')->where('id',$idEn)->first();


        return view('roastprofile.edit',compact('data'));
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
            $validated = $request->validate([
                'deskripsi' => 'required|string',
                'charge_temp' => 'required|numeric',
                'charge_time_sec' => 'required|numeric',
                'tp_temp' => 'required|numeric',
                'tp_time_sec' => 'required|numeric',
                'de_temp' => 'required|numeric',
                'de_time_sec' => 'required|numeric',
                'fc_temp' => 'required|numeric',
                'fc_time_sec' => 'required|numeric',
                'sc_temp' => 'required|numeric',
                'sc_time_sec' => 'required|numeric',
                'drop_temp' => 'required|numeric',
                'drop_time_sec' => 'required|numeric',
            ]);

            DB::table('roast_profile')->where('id', $id)->update([
                'deskripsi' => $validated['deskripsi'],
                'charge_temp' => $validated['charge_temp'],
                'charge_time_sec' => $validated['charge_time_sec'],
                'tp_temp' => $validated['tp_temp'],
                'tp_time_sec' => $validated['tp_time_sec'],
                'de_temp' => $validated['de_temp'],
                'de_time_sec' => $validated['de_time_sec'],
                'fcs_temp' => $validated['fc_temp'],
                'fcs_time_sec' => $validated['fc_time_sec'],
                'sc_temp' => $validated['sc_temp'],
                'sc_time_sec' => $validated['sc_time_sec'],
                'drop_temp' => $validated['drop_temp'],
                'drop_time_sec' => $validated['drop_time_sec'],
                'updated_at' => now(),
            ]);

            return redirect()->route('roast_profile.index')->with('success', 'Mengubah Roast Profile!');
        }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('roast_profile')->where('id',$id)->delete();
        return redirect()->back()->with('success','Menghapus Roast Profile');
    }
}
