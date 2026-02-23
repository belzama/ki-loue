<?php

namespace App\Http\Controllers;

use App\Models\SysParam;
use Illuminate\Http\Request;

class SysParamController extends Controller
{
    /**
     * Liste des paramètres système
     */
    public function index()
    {
        return response()->json(SysParam::all(), 200);
    }

    /**
     * Enregistrer un nouveau paramètre
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'  => 'required|string|unique:sys_params,code',
            'value' => 'required|string',
            'desc'  => 'nullable|string',
        ]);

        $param = SysParam::create($request->all());

        return response()->json($param, 201);
    }

    /**
     * Afficher un paramètre
     */
    public function show($id)
    {
        $param = SysParam::findOrFail($id);
        return response()->json($param);
    }

    /**
     * Mettre à jour un paramètre
     */
    public function update(Request $request, $id)
    {
        $param = SysParam::findOrFail($id);

        $request->validate([
            'code'  => 'required|string|unique:sys_params,code,' . $param->id,
            'value' => 'required|string',
            'desc'  => 'nullable|string',
        ]);

        $param->update($request->all());

        return response()->json($param);
    }

    /**
     * Supprimer un paramètre
     */
    public function destroy($id)
    {
        SysParam::findOrFail($id)->delete();
        return response()->json(['message' => 'Paramètre supprimé'], 200);
    }
}
