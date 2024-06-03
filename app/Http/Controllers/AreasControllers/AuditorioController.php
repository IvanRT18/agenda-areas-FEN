<?php

namespace App\Http\Controllers\AreasControllers;

use App\Models\AreasModels\AuditorioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Mail\AreaReservadaMail;
use App\Models\DiaDeshabilitado;
use Illuminate\Support\Facades\Mail;

class AuditorioController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        $errors = [];

        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada_auditorio' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'hora_reserva_inicio_auditorio' => 'required|date_format:H:i',
            'hora_reserva_final_auditorio' => 'required|date_format:H:i|after:hora_reserva_inicio_auditorio',
            'numero_microfonos_auditorio' => 'required|integer|min:0|max:2',
        ]);

        // Verificar si la fecha seleccionada está deshabilitada
        $fechaReservada = $request->fecha_reservada_auditorio;
        if (DiaDeshabilitado::where('diaDeshabiliado', $fechaReservada)->exists()) {
            $errors['fecha_reservada_auditorio'] = 'La fecha seleccionada no está disponible para reservaciones.';
        }

        // Verificar si la hora de inicio no está reservada
        $horaInicio = $request->hora_reserva_inicio_auditorio;
        if (AuditorioModel::where('hora_reserva_inicio', $horaInicio)->exists()) {
            $errors['hora_reserva_inicio'] = 'La hora seleccionada de inicio ya está reservada.';
        }

        // Verificar si la hora de finalización no está reservada
        $horaFinal = $request->hora_reserva_final_auditorio;
        if (AuditorioModel::where('hora_reserva_final', $horaFinal)->exists()) {
            $errors['hora_reserva_final'] = 'La hora seleccionada de finalización ya está reservada.';
        }

        // Combinar los errores de validación
        $validator->errors()->add('general', collect($errors)->flatten());

        // Si hay errores, lanzar una excepción de validación
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Crear una nueva instancia de la reserva
        $reservacion = new AuditorioModel();
        $reservacion->rpe = session('user');
        $reservacion->fecha_reservada = $fechaReservada;
        $reservacion->hora_reserva_inicio = $horaInicio;
        $reservacion->hora_reserva_final = $horaFinal;
        $reservacion->numero_microfonos = $request->numero_microfonos_auditorio;

        // Establecer los valores booleanos basados en las entradas del formulario
        $reservacion->usa_proyector = $request->has('usa_proyector_auditorio');
        $reservacion->usa_computadora = $request->has('usa_computadora_auditorio');
        $reservacion->usa_bocinas = $request->has('usa_bocinas_auditorio');

        // Guardar la reserva
        $reservacion->save();

        $correo = new AreaReservadaMail($reservacion->rpe, 'Iván', $reservacion->fecha_reservada, $reservacion->hora_reserva_inicio, $reservacion->hora_reserva_final);
        Mail::to('ivan18rt@outlook.com')->send($correo);

        return redirect()->back()->with('status', 'Se ha reservado el auditorio correctamente');
    }

    public function showAll(Request $request)
    {
        $reservaciones = AuditorioModel::all();

        return view('areas.auditorio.admin', compact('reservaciones'));
    }

    public function showHorasDia(Request $request)
    {
        // $reservaciones = AuditorioModel::where('fechaReservada', $fecha)->get();
        return view('areas.auditorio.ver');
    }

    // Recibe una fecha (string) y devuelve las horas que se han agendado ese día
    public function horasAgendadas($fecha)
    {
        $horasAgendadas = AuditorioModel::where('fecha_reservada', $fecha)->get();
        return response()->json($horasAgendadas);
    }

    // Edita una reservacion
    public function edit($id){
        $reservacion = AuditorioModel::find($id);
        return view('areas.auditorio.editar', compact('reservacion'));
    }

    public function update(Request $request, $id)
    {
        $errors = [];

        // Validación de datos de entrada
        $validator = Validator::make($request->all(), [
            'fecha_reservada' => 'required|date',
            'hora_reserva_inicio' => 'required|date_format:H:i',
            'hora_reserva_final' => 'required|date_format:H:i|after:hora_reserva_inicio',
            'numero_microfonos' => 'required|integer|min:0|max:2',
        ]);

        // Verificar si la fecha seleccionada está deshabilitada
        $fechaReservada = $request->fecha_reservada_auditorio;
        if (DiaDeshabilitado::where('diaDeshabiliado', $fechaReservada)->exists()) {
            $errors['fecha_reservada_auditorio'] = 'La fecha seleccionada no está disponible para reservaciones.';
        }

        // Verificar si la hora de inicio no está reservada
        $horaInicio = $request->hora_reserva_inicio_auditorio;
        if (AuditorioModel::where('hora_reserva_inicio', $horaInicio)->exists()) {
            $errors['hora_reserva_inicio'] = 'La hora seleccionada de inicio ya está reservada.';
        }

        // Verificar si la hora de finalización no está reservada
        $horaFinal = $request->hora_reserva_final_auditorio;
        if (AuditorioModel::where('hora_reserva_final', $horaFinal)->exists()) {
            $errors['hora_reserva_final'] = 'La hora seleccionada de finalización ya está reservada.';
        }

        // Combinar los errores de validación
        $validator->errors()->add('general', collect($errors)->flatten());

        // Si hay errores, lanzar una excepción de validación
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $reservacion = AuditorioModel::find($id);
        $reservacion->id = $request->input('id');
        $reservacion->rpe = $request->input('rpe');
        $reservacion->fecha_reservada = $request->input('fecha_reservada');
        $reservacion->hora_reserva_inicio = $request->input('hora_reserva_inicio');
        $reservacion->hora_reserva_final = $request->input('hora_reserva_final');
        $reservacion->usa_proyector = $request->input('usa_proyector');
        $reservacion->numero_microfonos = $request->input('numero_microfonos');
        $reservacion->usa_computadora = $request->input('usa_computadora');
        $reservacion->usa_bocinas = $request->input('usa_bocinas');
        $reservacion->update();
        return redirect()->back()->with('status','Reservación actualizada correctamente');
    }

    public function delete($id)
    {
        $reservacion = AuditorioModel::find($id);
        $reservacion->delete();
        return redirect()->back()->with('status', 'Reservación borrada correctamente');
    }

    public function diasInhabiles()
    {
        return view('dias_deshabilitados');
    }

    public function configure()
    {
        return view('areas.auditorio.configure');
    }

    public function updateConfiguration()
    {

    }
}
