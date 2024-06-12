<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Reservación</title>
</head>
<body>

    <p>Se ha realizado una nueva reservación en el auditorio:</p>

    <p>Nombre completo: {{ $nombreCompleto }}</p>
    <p>RPE: {{ $rpe }}</p>
    <p>Fecha de Reservación: {{ $fechaReservada }}</p>
    <p>Hora de Inicio: {{ $horaInicio }}</p>
    <p>Hora de Finalización: {{ $horaFinal }}</p>

    <p>¡Gracias!</p>
</body>
</html>
