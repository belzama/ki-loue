<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('pays')->get()
        ]);
    }

    public function create()
    {
        $pays = Pays::all(); // récupérer la liste des pays
        $users = User::all(); // récupérer la liste des pays
        return view('admin.users.create', compact('pays','users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pays_id' => 'required|exists:pays,id',
            'user_id' => 'nullable|exists:users,id',

            'code' => 'required|string|max:50|unique:users,code',
            'nom' => 'required|string|max:150',
            'prenom' => 'required|string|max:150',
            'raison_sociale' => 'required|string|max:150',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|string|min:6',

            'telephone' => 'required|string|max:30',
            'whatsapp' => 'required|string|max:30',

            'type' => 'required|in:Société,Particulier',
            'role' => 'required|in:Admin,User',

            'taux_commission' => 'required|numeric|min:0',
            'taux_commission_sponsor' => 'required|numeric|min:0',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        $pays = Pays::all();
        $users = User::where('id','!=',$user->id)->get(); // éviter lui-même
        return view('admin.users.edit', compact('user','pays','users'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'pays_id' => 'required|exists:pays,id',
            'user_id' => 'nullable|exists:users,id',

            'code' => 'required|string|max:50|unique:users,code',
            'nom' => 'required|string|max:150',
            'prenom' => 'required|string|max:150',
            'raison_sociale' => 'required|string|max:150',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|string|min:6',

            'telephone' => 'required|string|max:30',
            'whatsapp' => 'required|string|max:30',

            'type' => 'required|in:Société,Particulier',
            'role' => 'required|in:Admin,User',

            'taux_commission' => 'required|numeric|min:0',
            'taux_commission_sponsor' => 'required|numeric|min:0',
        ]);


        $user->update($data);
        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }
}
