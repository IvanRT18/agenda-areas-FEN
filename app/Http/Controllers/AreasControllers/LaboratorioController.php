<?php

namespace App\Http\Controllers\AreasControllers;

use App\Models\AreasModels\LaboratorioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\AreaReservadaMail;
use App\Models\Usuario;

class LaboratorioController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        $usuario = Usuario::where('clave', session('user'))->first();
        $reservacion = new LaboratorioModel();
        $reservacion->rpe = $usuario->getRpe();
        $reservacion->nombre_completo = $usuario->getNombreCompleto();
        $reservacion->numero_equipos = $request->numero_equipos;
        $reservacion->fecha_reservada = $request->fecha_reservada;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio;
        $reservacion->hora_reserva_final = $request->hora_reserva_final;
        if ($request->usa_proyector == 'true') //Si es 1 (osea si se selecciono)
            $reservacion->usa_proyector = true;
        else
            $reservacion->usa_proyector = false;
        $reservacion->req_adicionales = $request->req_adicionales;
        $reservacion->materia = $request->materia;
        $reservacion->actividad = $request->actividad;
        $reservacion->nombre_curso = $request->nombre_curso;
        $reservacion->save();

        // Mail::to('ivan18rt@outlook.com')->send(new AreaReservadaMail);

        return redirect()->back()->with('status', 'Se ha reservado el laboratorio correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = LaboratorioModel::all();

        return view('AdminPanel', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = LaboratorioModel::where('fechaReservada', $fecha)->get();
        return view('reservacionesLaboratorio');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = LaboratorioModel::where('fecha_reservada', $fecha)->get();

        return response()->json($horasAgendadas);
    }

    // Edita una cita
    public function editaCita($idCita){
        $cita = LaboratorioModel::find($idCita);
        return view('editCitaLaboratorio', compact('cita'));
    }

    public function updateCita(Request $request, $id)
    {
        $cita = LaboratorioModel::find($id);
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
