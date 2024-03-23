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
        $diaDes = new DiaDeshabilitado();
        $diaDes->diaDesInicio = $request->input('agregarDiaDesactivadoInput');
        $diasdata = $request->input('agregarDiaDesactivadoInput');
        $fechas = $this->extraerFechas($diasdata);

        
        foreach ($fechas as $fecha) {
            $diaDeshabilitadoFecha = new DiaDeshabilitado();
    
            // Asignar la fecha al modelo
            $diaDeshabilitadoFecha->diaDesInicio = $fecha;
    
            // Guardar el modelo
            $diaDeshabilitadoFecha->save();
        }
        return redirect()->back()->with('status', 'Dia deshabilitado correctamente');
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

    public function extraerFechas($cadenaFechas) {
        // Dividir la cadena en base a la coma como delimitador
        $fechasSeparadas = explode(', ', $cadenaFechas);

        // Eliminar espacios en cada fecha y devolver el resultado
        return array_map('trim', $fechasSeparadas);
    }
}
