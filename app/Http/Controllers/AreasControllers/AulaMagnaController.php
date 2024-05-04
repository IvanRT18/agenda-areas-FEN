<?php

namespace App\Http\Controllers\AreasControllers;

use App\Models\AreasModels\LaboratorioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\AreasModels\AulaMagnaModel;

class AulaMagnaController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        $reservacion = new AulaMagnaModel();
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $request->fecha_reservada_magna;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio_magna;
        $reservacion->hora_reserva_final = $request->hora_reserva_final_magna;
        $reservacion->numero_microfonos = $request->numero_microfonos_magna;

        if ($request->usa_proyector_magna == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_proyector = true;
        else
            $reservacion->usa_proyector = false;

        if ($request->usa_computadora_magna == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_computadora = true;
        else
            $reservacion->usa_computadora = false;

        if ($request->usa_bocinas_magna == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_bocinas = true;
        else
            $reservacion->usa_bocinas = false;

        $reservacion->save();

        return redirect()->back()->with('status', 'Se ha reservado el aula magna correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = AulaMagnaModel::all();

        return view('AdminPanel', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = LaboratorioModel::where('fechaReservada', $fecha)->get();
        return view('areas.reservaciones_aula_magna');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = AulaMagnaModel::where('fecha_reservada', $fecha)->get();

        return response()->json($horasAgendadas);
    }

    // Edita una cita
    public function edit($idCita){
        $cita = AulaMagnaModel::find($idCita);
        return view('laboratorio_editar_view', compact('cita'));
    }

    public function update(Request $request, $id)
    {
        $cita = AulaMagnaModel::find($id);
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
        $citaLab = LaboratorioModel::find($idCita);
        $citaLab->delete();
        return redirect()->back()->with('status', 'Cita borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('diasDesactivados');
    }
}
