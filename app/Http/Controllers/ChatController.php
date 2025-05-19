<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterKelas;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class ChatController extends Controller
{
    public function getData(Request $request)
    {
        $id = Auth::user()->id;
        $lastId = $request->input('last_id');

        $query = DB::table('chat_user as a')
            ->leftJoin('chat_audio as b', 'a.id_chat', '=', 'b.id')
            ->leftJoin('users as c', 'a.id_user', '=', 'c.id')
            ->select('a.id', 'a.message', 'b.file as nama_file', DB::raw("CONCAT('" . asset('chataudio') . "/', b.file) as audio_url"))
            ->where('c.id', $id);

        if ($lastId) {
            $query->where('a.id', '>', $lastId);
        }

        $data = $query->orderBy('a.created_at')->get();

        return response()->json($data);
    }

    public function chatAudio()
    {
        $kelas = MasterKelas::all();
        return view('chat.index', compact('kelas'));
    }

    public function chatAudioList()
    {
        return view('chat.list');
    }

    public function getUser(Request $request)
    {

        $search = $request->get('q');
        $id_kelas = $request->get('id_kelas');

        if (!$id_kelas) {
            // Kalau kelas belum dipilih, return kosong
            return response()->json([]);
        }

        // Step 1: Ambil semua email siswa dari MasterSiswa berdasarkan kelas
        $emailsSiswa = MasterSiswa::where('id_kelas', $id_kelas)
            ->pluck('email')
            ->toArray();

        // Step 2: Ambil user dengan email yang cocok dan role = 0
        $query = User::where('role', 0)
            ->whereIn('email', $emailsSiswa);

        // Step 3: Jika ada input pencarian, filter berdasarkan nama/email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Step 4: Ambil maksimal 20 user untuk efisiensi
        $users = $query->get();

        // Step 5: Format untuk Select2
        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => $user->name,
            ];
        });

        return response()->json($formattedUsers);
    }




    public function store(Request $request)
    {

        // dd($request->userid);
        // Validasi file audio
        $request->validate([
            'audio' => 'required|mimes:mp3,wav,aac',
            'userid' => 'required|array',
            'userid.*' => 'exists:users,id',
            'id_kelas' => 'exists:master_kelas,id_kelas'
        ]);

        // Simpan file audio ke folder public/chat_audio
        $file = $request->file('audio');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('chataudio'), $fileName);

        // Simpan informasi audio ke database menggunakan DB::table()->insert()
        $chatAudioId = DB::table('chat_audio')->insertGetId([
            'file' => $fileName,
            'title' => $request->title,
            'created_at' => now(),
        ]);

        // Simpan user_id ke tabel chat_user menggunakan DB::table()->insert()
        $chatUserData = [];
        foreach ($request->userid as $userId) {
            $chatUserData[] = [
                'id_chat' => $chatAudioId,
                'id_user' => $userId,
                'created_at' => now(),
                'message' => $request->message
            ];
        }

        DB::table('chat_user')->insert($chatUserData);

        return redirect()->back()->with('message', 'data berhasil di simpan');
    }

    public function getAllData()
    {

        $data = DB::table('chat_user as a')
            ->leftJoin('chat_audio as b', 'a.id_chat', '=', 'b.id')
            ->leftJoin('users as c', 'a.id_user', '=', 'c.id')
            ->select('a.*', 'c.name as nama_murid', 'b.file as file_name', 'b.title')
            ->orderBy('a.id', 'desc')
            ->get();
        return response()->json(['data' => $data]);
    }
}
