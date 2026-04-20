<?php

namespace App\Http\Controllers\API\HistorialsC;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistorialDepProgForm;
use Illuminate\Support\Facades\Validator;
class historialDepProgFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'res'=> true,
            'data'=> HistorialDepProgForm::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $val = Validator::make($request->all(), [
            'id_departamento' => 'required',
            'id_prog_form' => 'required',
            'id_curso' => 'required'
        ]);
        if ($val->fails()) {
            return response()->json([
                'res'=> false,
                'message'=> $val->errors(),
                'status'=> 400
            ], 400);
        }
        $hist =HistorialDepProgForm::
            where('id_departamento', $request->id_departamento)
            ->where('id_prog_form', $request->id_prog_form)
            ->where('id_curso', $request->id_curso)->first();
        if(!$hist){
            return response()->json([
                'res'=> false,
                'message'=> 'No se encontro el registro'
            ], 400);
        }
        $hist->delete();
        return response()->json([
            'res' => true,
            'message' => 'Se elimino el registro'
        ], 200);
    }
    public function carrerasPorDepartamento($id)
{
    $carreras = DB::table('departamento_prog_d_form')
        ->join(
            'programa_de_formacion',
            'departamento_prog_d_form.id_prog_form',
            '=',
            'programa_de_formacion.id'
        )
        ->where('departamento_prog_d_form.id_departamento', $id)
        ->select(
            'programa_de_formacion.id',
            'programa_de_formacion.nombre'
        )
        ->get();

    return response()->json($carreras);
}
}
