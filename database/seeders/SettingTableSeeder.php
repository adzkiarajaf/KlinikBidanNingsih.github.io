<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting')->insert([
            'id_setting' => 1,
            'nama_perusahaan' => 'Klinik Bidan Ningsih',
            'alamat' => 'Jl.Tanyung gedong RT 2 RW 26 No 3 Grogol Jakarta Barat',
            'telepon' => '+62812-1927-9978',
            'tipe_nota' => 1, // kecil
            'path_logo' => asset('img/123.png'),
            'path_kartu_member' => asset('/img/123.png')
        ]);
    }
}