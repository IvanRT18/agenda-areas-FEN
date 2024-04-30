<?php

namespace App\Http\Controllers\AreasControllers;

use App\Models\AreasModels\AuditorioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\Controller;


class AuditorioController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        $reservacion = new AuditorioModel();
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $request->fecha_reservada_auditorio;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio_auditorio;
        $reservacion->hora_reserva_final = $request->hora_reserva_final_auditorio;


        if ($request->usa_proyector_auditorio == null) //Si es 1 (osea si se selecciono)
            $reservacion->usa_proyector = false;
        else
            $reservacion->usa_proyector = true;

        if ($request->usa_bocinas_auditorio === null) //Si es 1 (osea si se selecciono)
            $reservacion->usa_bocinas = false;
        else
            $reservacion->usa_bocinas = true;

        if ($request->usa_computadora_auditorio == null) //Si es 1 (osea si se selecciono)
            $reservacion->usa_computadora = true;
        else
            $reservacion->usa_computadora = false;


        $reservacion->numero_microfonos = $request->numero_microfonos_auditorio;
        $reservacion->usa_computadora = $request->usa_computadora_auditorio;
        $reservacion->usa_bocinas = $request->usa_bocinas_auditorio;
        $reservacion->save();

        return redirect()->back()->with('status', 'Se ha reservado el área correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = AuditorioModel::all();

        return view('AdminPanel', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = AuditorioModel::where('fechaReservada', $fecha)->get();
        return view('reservaciones_auditorio');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = AuditorioModel::where('fecha_reservada', $fecha)->get();
        return response()->json($horasAgendadas);
    }

    // Edita una cita
    public function editaCita($idCita){
        $cita = AuditorioModel::find($idCita);
        return view('editCitaLaboratorio', compact('cita'));
    }

    public function updateCita(Request $request, $id)
    {
        $cita = AuditorioModel::find($id);
        $cita->id = $request->input('id');
        $cita->numEquipos = $request->input('numEquipos');
        $cita->fechaReservada = $request->input('fechaReservada');
        $cita->horaReservaInicio = $request->input('horaReservaInicio');
        $cita->horaReservaFinal = $request->input('horaReservaFinal');
        $cita->usaProyector = $request->input('usaProyector');
        $cita->reqAdicionales = $request->input('reqAdicionales');
        $cita->Rpe = $request->input('Rpe');
        $cita->nombreCompleto = $request->input('nombreCompleto');
        $cita->materia = $request->input('materia');
        $cita->actividad = $request->input('actividad');
        $cita->nombreCurso = $request->input('nombreCurso');
        $cita->update();
        return redirect()->back()->with('status','Cita actualizada correctamente');
    }

    public function borrarCita($idCita)
    {
        $citaLab = AuditorioModel::find($idCita);
        $citaLab->delete();
        return redirect()->back()->with('status', 'Cita borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('diasDesactivados');
    }
}
