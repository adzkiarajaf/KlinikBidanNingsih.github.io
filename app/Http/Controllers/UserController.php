<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = user::all(); 
        return view('user.index', compact('user'));
    }

    public function data()
    {
        $user = User::orderBy('id', 'desc')->get();
        foreach ($user as $item) {
            $row = array();
            $row['name'] = $item->users['name'];
            $row['email'] = $item->users['email'];
            $row['password'] = $item->users['password'];
            $row['level'] = $item->users['level'];
        }

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

        $namaUser= $request->name;
        // Check for duplicate User
        $existingUser = User::where('name', $namaUser)->first();
        if ($existingUser) {
            return redirect()->back()->withErrors(['name' => 'User dengan nama yang sama sudah ada.']);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        // Ambil nilai level dari inputan form
        $level = $request->input('level');
        // Pastikan nilai level hanya 0 atau 1, jika tidak, set nilai default menjadi 0
        $user->level = in_array($level, [0, 1]) ? $level : 0;

        $user->save();

        return redirect()->route('kategori.index'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user);
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
        $user = User::find($id);

        // Memastikan data name dan email tetap di-update
        $user->name = $request->name;
        $user->email = $request->email;

        // Jika password tidak kosong, update password
        if ($request->has('password') && $request->password != "") {
            $user->password = bcrypt($request->password);
        }

        // Memastikan level tidak diubah jika level adalah 0 (Owner)
        if ($user->level != 0) {
            $user->level = $request->level;
        }

        $user->update();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();

        return response(null, 204);
    }

    public function profil()
    {
        $profil = auth()->user();
        return view('user.profil', compact('profil'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();
        
        $user->name = $request->name;
        if ($request->has('password') && $request->password != "") {
            if (Hash::check($request->old_password, $user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                } else {
                    return response()->json('Konfirmasi password tidak sesuai', 422);
                }
            } else {
                return response()->json('Password lama tidak sesuai', 422);
            }
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            $user->foto = "/img/$nama";
        }

        $user->update();

        return redirect()->back();
    }

    public function penjualan()
    {
        return $this->hasMany('App/penjualan','id_user', 'id');
    }
}