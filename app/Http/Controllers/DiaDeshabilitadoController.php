<?php

namespace App\Http\Controllers;

use App\Models\DiaDeshabilitado;
use Illuminate\Http\Request;

class DiaDeshabilitadoController extends Controller
{
    //Muestra la vista para deshabilitar días
    public function index()
    {
        $diasDeshabilitados = DiaDeshabilitado::all();
        return view('dias_deshabilitados', compact('diasDeshabilitados'));
    }

    //Deshabilita los días seleccionados
    public function store(Request $request)
    {
        $this->habilitaTodosDias();
        if ($request->input('agregarDiaDesactivadoInput') !== null) {
            $diasdata = $request->input('agregarDiaDesactivadoInput');
            $fechas = $this->extraerFechas($diasdata);

            foreach ($fechas as $fecha) {
                $diaDeshabilitadoFecha = new DiaDeshabilitado();
        
                // Asignar la fecha al modelo
                $diaDeshabilitadoFecha->dias_deshabilitado = $fecha;
        
                // Guardar el modelo
                $diaDeshabilitadoFecha->save();
            }
            return redirect()->back()->with('status', 'Dia deshabilitado correctamente');
        }
        return redirect()->back();
        
    }

    //Obtiene todos los días deshabilitados y los devuelve como json
    public function getAllDisabledDays(){
        $diasDeshabilitados = DiaDeshabilitado::all();
        return response()->json($diasDeshabilitados);
    }

    //Habilita un día desactivado
    public function habilitaDia($id){
        $diaDeshabilitado = DiaDeshabilitado::find($id);
        $diaDeshabilitado->delete();
        return redirect()->back()->with('status', 'Día nuevamente habilitado para agendar');
    }

    //Restablece todos los días del calendario como habilitados para agendar
    public function habilitaTodosDias(){
        DiaDeshabilitado::truncate();
        return redirect()->back()->with('status', 'Se han borrado todos los dias deshabilitados');
    }

    //Obtiene todas las fechas selecionadas del calendario y los regresa en un formato mas fácil de manejar    
    public function extraerFechas($cadenaFechas) {
        // Dividir la cadena en base a la coma como delimitador
        $fechasSeparadas = explode(', ', $cadenaFechas);

        // Eliminar espacios en cada fecha y devolver el resultado
        return array_map('trim', $fechasSeparadas);
    }
}
