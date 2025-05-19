<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = "member";

    protected $fillable = [
        'nama', 'telpon', 'alamat', 'akun_bank', 'nama_bank', 'ref_code', 'member_code', 'ktp', 'payment', 'tgl_reg', 'hapus',
    ];

    public static function generateMemberCode($ref_code)
    {
        // Mendapatkan jumlah anggota dengan kode referal yang sama
        $total = Member::where('ref_code', $ref_code)->count();

        // Membuat kode member berdasarkan jumlah anggota
        $member_code = $ref_code . ($total + 1);

        return $member_code;
    }

    public static function add_nasabah($nama, $telpon, $alamat, $akun_bank, $nama_bank, $ref_code, $member_code, $ktp_filename, $payment_filename)
    {
        $field = [
            "nama" => $nama,
            "telpon" => $telpon,
            "alamat" => $alamat,
            "akun_bank" => $akun_bank,
            "nama_bank" => $nama_bank,
            "ref_code" => $ref_code,
            "member_code" => $member_code,
            "ktp" => $ktp_filename,
            "payment" => $payment_filename,
            "tgl_reg" => date('d M Y'),
        ];
        Member::create($field);
    }

    public static function data_member()
    {
        $data = Member::where("hapus", "0")->orderBy('id', 'DESC')->get();
        return $data;
    }

    public static function deletekaryawan($id)
    {
        $data = Member::where("id", $id)->first();
        $data->hapus = "1";
        $data->save();
    }

    public static function detail_member($member_code)
    {
        $detail_user = Member::where("member_code", $member_code)->first();
        return $detail_user;
    }

}
