<?php

namespace App\Http\Controllers;

use App\Models\AuditorioCita;
use Illuminate\Http\Request;

class AuditorioCitasController extends Controller
{
    public function index()
    {
        return view('home');
    }


    public function store(Request $request)
    {
        $auditorioCita = new AuditorioCita();
        $auditorioCita->numEquipos = $request->numEquipos;
        $auditorioCita->fechaReservada = $request->fechaReservada;
        $auditorioCita->horaReservada = $request->horaReservada;
        if ($request->usaProyector == 'true') //Si es 1 (osea si se selecciono)
            $auditorioCita->usaProyector = true;
        else
            $auditorioCita->usaProyector = false;

        // $auditorioCita->usaProyector = $request->usaProyector;
        $auditorioCita->reqAdicionales = $request->reqAdicionales;
        $auditorioCita->Rpe = $request->Rpe;
        $auditorioCita->nombreCompleto = $request->nombreCompleto;
        $auditorioCita->materia = $request->materia;
        $auditorioCita->actividad = $request->actividad;
        $auditorioCita->nombreCurso = $request->nombreCurso;
        $auditorioCita->save();

        return redirect('inicio')->with('status', 'Se ha reservado el área correctamente');
    }
}
