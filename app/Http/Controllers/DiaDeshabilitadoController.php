<?php

namespace App\Http\Controllers;

use App\Models\DiaDeshabilitado;
use Illuminate\Http\Request;

class DiaDeshabilitadoController extends Controller
{
    public function index()
    {
        $diasDeshabilitados = DiaDeshabilitado::all();
        return view('diasDesactivados', compact('diasDeshabilitados'));
    }

    public function store(Request $request)
    {
        $this->eliminaAllDiasDeshabilitados();
        if ($request->input('agregarDiaDesactivadoInput') !== null) {
            $diasdata = $request->input('agregarDiaDesactivadoInput');
            $fechas = $this->extraerFechas($diasdata);

            foreach ($fechas as $fecha) {
                $diaDeshabilitadoFecha = new DiaDeshabilitado();
        
                // Asignar la fecha al modelo
                $diaDeshabilitadoFecha->diaDeshabiliado = $fecha;
        
                // Guardar el modelo
                $diaDeshabilitadoFecha->save();
            }
            return redirect()->back()->with('status', 'Dia deshabilitado correctamente');
        }
        return redirect()->back();
        
    }

    public function allDiasDeshabilitados(){
        $diasDeshabilitados = DiaDeshabilitado::all();

        return response()->json($diasDeshabilitados);
    }

    public function eliminaDiaDeshabilitado($idDia){
        $diaDisabled = DiaDeshabilitado::find($idDia);
        $diaDisabled->delete();
        return redirect()->back()->with('status', 'Día nuevamente habilitado para agendar');
    }

    public function eliminaAllDiasDeshabilitados(){
        DiaDeshabilitado::truncate();
        return redirect()->back()->with('status', 'Se han borrado todos los dias deshabilitados');
    }

    public function extraerFechas($cadenaFechas) {
        // Dividir la cadena en base a la coma como delimitador
        $fechasSeparadas = explode(', ', $cadenaFechas);

        // Eliminar espacios en cada fecha y devolver el resultado
        return array_map('trim', $fechasSeparadas);
    }
}
