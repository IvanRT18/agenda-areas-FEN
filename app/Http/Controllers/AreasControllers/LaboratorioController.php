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
        // $reservacion->nombre_completo = $usuario->getNombreCompleto();
        $reservacion->numero_equipos = $request->numero_equipos;
        $reservacion->fecha_reservada = $request->fecha_reservada;
        $reservacion->hora_reserva_inicio = $request->hora_reserva_inicio;
        $reservacion->hora_reserva_final = $request->hora_reserva_final;
        if ($request->usa_proyector == 'true') //Si es 1 (osea si se selecciono)
        {
            $reservacion->usa_proyector = true;
            $reservacion->ubicacion = 'enfrente';
        }
        else{
            $reservacion->usa_proyector = false;
            $reservacion->ubicacion = 'detrás';
        }
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

        return view('areas.laboratorio.admin', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = LaboratorioModel::where('fechaReservada', $fecha)->get();
        return view('areas.laboratorio.ver');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = LaboratorioModel::where('fecha_reservada', $fecha)->get(['hora_reserva_inicio', 'hora_reserva_final', 'materia', 'actividad']);
        return response()->json($horasAgendadas);
    }

    // Edita una cita
    public function edit($id){
        $reservacion = LaboratorioModel::find($id);
        return view('areas.laboratorio.editar', compact('reservacion'));
    }

    public function update(Request $request, $id)
    {
        $reservacion = LaboratorioModel::find($id);
        $reservacion->id = $request->input('id');
        $reservacion->rpe = $request->input('rpe');
        $reservacion->nombre_completo = $request->input('nombre_completo');
        $reservacion->fecha_reservada = $request->input('fecha_reservada');
        $reservacion->hora_reserva_inicio = $request->input('hora_reserva_inicio');
        $reservacion->hora_reserva_final = $request->input('hora_reserva_final');
        $reservacion->numero_equipos = $request->input('numero_equipos');
        $reservacion->usa_proyector = $request->input('usa_proyector');
        $reservacion->req_adicionales = $request->input('req_adicionales');
        $reservacion->materia = $request->input('materia');
        $reservacion->actividad = $request->input('actividad');
        $reservacion->nombre_curso = $request->input('nombre_curso');
        $reservacion->update();
        return redirect()->back()->with('status','Reservación actualizada correctamente');
    }

    public function delete($id)
    {
        $reservacion = LaboratorioModel::find($id);
        $reservacion->delete();
        return redirect()->back()->with('status', 'Reservación borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('dias_deshabilitados');
    }
}
