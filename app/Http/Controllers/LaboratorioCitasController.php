<?php

namespace App\Http\Controllers;

use App\Models\LaboratorioCita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaboratorioCitasController extends Controller
{
    public function index()
    {
        return view('home');
    }


    public function store(Request $request)
    {
        $LabCompCita = new LaboratorioCita();
        $LabCompCita->numEquipos = $request->numEquipos;
        $LabCompCita->fechaReservada = $request->fechaReservada;
        $LabCompCita->horaReservaInicio = $request->horaReservaInicio;
        $LabCompCita->horaReservaFinal = $request->horaReservaFinal;
        if ($request->usaProyector == 'true') //Si es 1 (osea si se selecciono)
            $LabCompCita->usaProyector = true;
        else
            $LabCompCita->usaProyector = false;

        // $LabCompCita->usaProyector = $request->usaProyector;
        $LabCompCita->reqAdicionales = $request->reqAdicionales;
        $LabCompCita->Rpe = $request->Rpe;
        $LabCompCita->nombreCompleto = $request->nombreCompleto;
        $LabCompCita->materia = $request->materia;
        $LabCompCita->actividad = $request->actividad;
        $LabCompCita->nombreCurso = $request->nombreCurso;
        $LabCompCita->save();

        return redirect('inicio')->with('status', 'Se ha reservado el área correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = LaboratorioCita::all();
        var_dump($reservaciones);

        return view('muestraReservaciones', ['reservaciones' => $reservaciones]);
    }
}
