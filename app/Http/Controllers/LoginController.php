<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    //
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('login');
        }
    }


    public function actionLogin(Request $request)
    {
        try {
            $user = User::where('nip', $request->nip)->first();

            if ($user) {
                // Check User status
                if ($user->status == 0) {
                    Session::flash('inactive', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator untuk informasi lebih lanjut.');
                    return redirect('/login');
                }

                $datalogin = [
                    'email' => $user->email, // Use the email associated with the NIP
                    'password' => $request->password,
                ];

                if (Auth::attempt($datalogin)) {
                    // Check if password is still default
                    if ($user->first_login == 1) {
                        //check user apakah baru pertama login bukan
                        //kalo pertama login first loginnya 1
                        //kalo user udah ganti password maka first loginnya jadi 0
                        return redirect()->route('change.view');
                    }

                    // Jika role siswa, cek status siswa
                    if (Auth::user()->role == 0) {
                        $siswa = MasterSiswa::where('email', $user->email)->first();
                        if ($siswa && $siswa->sts == 0) {
                            Auth::logout();
                            Session::flash('inactive', 'Akun siswa Anda telah dinonaktifkan. Silakan hubungi administrator untuk informasi lebih lanjut.');
                            return redirect('/login');
                        }
                        return redirect('/siswaIn');
                    }

                    return redirect('/home');
                }
            }

            Session::flash('error', 'NIP / Password Salah !');
            return redirect('/login');
        } catch (\Exception $e) {
            Session::flash('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            return redirect('/login');
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public function actionLogout()
    {
        Auth::logout();
        Session::flush(); // Add session flush for complete logout
        return redirect('/');
    }

    public function changeView()
    {
        if (Auth::check()) {
            return view('change');
        } else {
            return view('login');
        }
    }
    public function changePassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui password. Periksa kembali input Anda.');
        }

        // Jika validasi sukses
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->first_login = 0; // Set jadi 0 menandakan bukan login pertama
        $user->save();

        session()->flash('success', 'Password berhasil diperbarui.');

        // Redirect sesuai role
        if ($user->role == 0) {
            return redirect('/siswaIn');
        } else {
            return redirect('/home');
        }
    }
}
