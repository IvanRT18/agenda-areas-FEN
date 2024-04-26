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
        $reservacion->rpe = $request->rpe;
        $reservacion->fechaReservada = $request->fechaReservada;
        $reservacion->horaReservaInicio = $request->horaReservaInicio;
        $reservacion->horaReservaFinal = $request->horaReservaFinal;
        if ($request->usaProyector == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usaProyector = true;
        else
            $reservacion->usaProyector = false;

        // $reservacion->usaProyector = $request->usaProyector;
        $reservacion->reqAdicionales = $request->reqAdicionales;
        $reservacion->Rpe = $request->Rpe;
        $reservacion->nombreCompleto = $request->nombreCompleto;
        $reservacion->materia = $request->materia;
        $reservacion->actividad = $request->actividad;
        $reservacion->nombreCurso = $request->nombreCurso;
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
        return view('reservacionesLaboratorio');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = AuditorioModel::where('fechaReservada', $fecha)->get();

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
