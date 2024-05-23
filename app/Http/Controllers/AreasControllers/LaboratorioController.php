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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día (solo horas)
    public function horasDisabled($fecha)
    {
        $disabledHours = LaboratorioModel::where('fecha_reservada', $fecha)->get();
        return response()->json($disabledHours);
    }

    // Edita una cita
    public function edit($id){
        $reservacion = LaboratorioModel::find($id);
        return view('areas.laboratorio.editar', compact('reservacion'));
    }

    public function update(Request $request, $id)
    {
        $errors = [];

        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada' => 'required|date',
            'hora_reserva_inicio' => 'required|date_format:H:i',
            'hora_reserva_final' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_equipos' => 'required|integer|min:0',
            'materia' => 'required|string',
            'actividad' => 'required|string',
        ]);

        // Si la validación falla, lanzar una excepción
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Verificar solapamiento de horarios
        $fechaReservada = $request->fecha_reservada;
        $horaInicio = $request->hora_reserva_inicio;
        $horaFinal = $request->hora_reserva_final;

        $overlap = LaboratorioModel::where('fecha_reservada', $fechaReservada)
            ->where(function($query) use ($horaInicio, $horaFinal) {
                $query->where(function($q) use ($horaInicio, $horaFinal) {
                    $q->where('hora_reserva_inicio', '<', $horaFinal)
                    ->where('hora_reserva_final', '>', $horaInicio);
                });
            })
            ->where('id', '<>', $id) // Excluir la reservación actual
            ->exists();

        if ($overlap) {
            $errors['hora_reserva'] = 'El rango de horas seleccionado ya esta reservado para esta fecha.';
            $validator->errors()->add('general', collect($errors)->flatten());
            throw new ValidationException($validator);
        }

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Actualizar la reservación si no hay solapamientos
        $reservacion = LaboratorioModel::find($id);
        $reservacion->rpe = $request->input('rpe');
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

        return redirect()->back()->with('status', 'Reservación actualizada correctamente');
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
