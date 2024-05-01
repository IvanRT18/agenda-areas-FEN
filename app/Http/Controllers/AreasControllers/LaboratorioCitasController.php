<?php

namespace App\Http\Controllers\AreasControllers;

use App\Models\AreasModels\LaboratorioCita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\AreaReservadaMail;

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

        // Mail::to('ivan18rt@outlook.com')->send(new AreaReservadaMail);

        return redirect()->back()->with('status', 'Se ha reservado el laboratorio correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = LaboratorioCita::all();

        return view('AdminPanel', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = LaboratorioCita::where('fechaReservada', $fecha)->get();
        return view('reservacionesLaboratorio');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = LaboratorioCita::where('fechaReservada', $fecha)->get();

        return response()->json($horasAgendadas);
    }

    // Edita una cita
    public function editaCita($idCita){
        $cita = LaboratorioCita::find($idCita);
        return view('editCitaLaboratorio', compact('cita'));
    }

    public function updateCita(Request $request, $id)
    {
        $cita = LaboratorioCita::find($id);
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
        $citaLab = LaboratorioCita::find($idCita);
        $citaLab->delete();
        return redirect()->back()->with('status', 'Cita borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('diasDesactivados');
    }
}
