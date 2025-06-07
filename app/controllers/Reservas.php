<?php

require RUTA_APP . "/librerias/Funciones.php";
require_once RUTA_APP . "/librerias/tfpdf/tfpdf.php";
require_once RUTA_APP . "/librerias/email.php";

// Definición del controlador Reservas
class Reservas extends Controlador
{
    private $reservaModelo;
    private $cuidadorModelo;
    private $mascotaModelo;
    private $duenoModelo;

    // Constructor: inicializa modelos y sesión
    public function __construct()
    {
        session_start();

        $this->reservaModelo = $this->modelo('Reservas_Model');
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
        $this->mascotaModelo = $this->modelo('Mascotas_Model');
        $this->duenoModelo = $this->modelo('Duenos_Model');
    }

    // Método para crear una reserva
    public function crear($cuidador_id)
    {
        // Obtiene los datos del cuidador
        $datosCuidador = $this->cuidadorModelo->obtenerPerfilCuidador($cuidador_id);
        if ($datosCuidador) {
            // Verifica que el usuario sea dueño
            if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] != 'dueno') {
                redireccionar('/autenticacion');
            }
            $errores = [];

            // Si el formulario fue enviado por POST
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Recoge y filtra los datos del formulario
                $datos = [
                    'duenio_id'      => $_SESSION['usuario_id'],
                    'cuidador_id'    => $cuidador_id,
                    'servicio'       => test_input($_POST['servicio']),
                    'fecha_inicio'   => test_input($_POST['fecha_inicio']),
                    'fecha_fin'      => test_input($_POST['fecha_fin']),
                    'mascotas'       => $_POST['mascotas'] ?? [],
                    'origen'         => test_rutas_locales($_POST['direccion_origen'] ?? ''),
                    'destino'        => test_rutas_locales($_POST['direccion_destino'] ?? '')
                ];

                $esTaxi = $datos['servicio'] === 'Taxi';
                if ($esTaxi) {
                    $datos['fecha_fin'] = $datos['fecha_inicio'];
                }

                // Validaciones base de los campos obligatorios
                if (
                    !comprobarDatos($datos['servicio']) ||
                    !comprobarFecha($datos['fecha_inicio']) ||
                    (!$esTaxi && !comprobarFecha($datos['fecha_fin'])) ||
                    !is_array($datos['mascotas']) ||
                    empty($datos['mascotas'])
                ) {
                    $errores[] = "Todos los campos obligatorios deben estar correctamente completados.";
                }

                // Validación de fecha de inicio
                if (!comprobarFecha_Inicio($datos['fecha_inicio'])) {
                    $errores[] = "La fecha de inicio debe ser posterior a hoy.";
                }

                // Validación de fecha de fin (si no es Taxi)
                if (!$esTaxi && !comprobarFecha_Fin($datos['fecha_inicio'], $datos['fecha_fin'])) {
                    $errores[] = "La fecha de fin debe ser posterior a la de inicio.";
                }

                //  Validar que las mascotas seleccionadas pertenecen al dueño autenticado
                if (!$this->mascotaModelo->mascotasValidas($datos['mascotas'], $_SESSION['usuario_id'])) {
                    $errores[] = "Una o más mascotas seleccionadas no son válidas.";
                } else {
                    // Validar que el cuidador admite el tipo y tamaño de cada mascota seleccionada

                    // Obtener tipos y tamaños de las mascotas seleccionadas
                    $mascotas = $this->mascotaModelo->obtenerTiposYTamanosMascotas($datos['mascotas']);

                    // Obtener lista de tipos y tamaños admitidos por el cuidador
                    $admite = $this->cuidadorModelo->obtenerTiposMascotas($cuidador_id);

                    // Convertir la lista de admitidos a un array asociativo para búsqueda rápida
                    $admitidos = [];
                    foreach ($admite as $adm) {
                        $admitidos[$adm->tipo_mascota . '-' . $adm->tamano] = true;
                    }

                    // Verificar cada mascota seleccionada, evitando mensajes repetidos por tipo-tamaño
                    $yaAvisado = [];
                    foreach ($mascotas as $mascota) {
                        $clave = $mascota->tipo . '-' . $mascota->tamano;
                        if (empty($admitidos[$clave]) && empty($yaAvisado[$clave])) {
                            $errores[] = "El cuidador no admite mascotas tipo '{$mascota->tipo}' y tamaño '{$mascota->tamano}'.";
                            $yaAvisado[$clave] = true;
                        }
                    }
                }

                // Validación de plazas disponibles del cuidador
                $totalConfirmadas = $this->reservaModelo->contarMascotasConfirmadas($cuidador_id, $datos['fecha_inicio'], $datos['fecha_fin']);
                $mascotasExtra    = count($datos['mascotas']);
                $max              = $this->reservaModelo->obtenerMaxMascotasCuidador($cuidador_id);

                if (($totalConfirmadas + $mascotasExtra) > $max) {
                    $errores[] = "El cuidador no dispone de plazas suficientes para las fechas indicadas.";
                }

                // Validación de solapamiento con reservas de domicilio
                if ($this->reservaModelo->cuidadorOcupadoPorDomicilio($cuidador_id, $datos['fecha_inicio'], $datos['fecha_fin'])) {
                    $errores[] = "El cuidador ya tiene una reserva de 'Cuidado a domicilio' en esas fechas.";
                }

                // Validación y cálculo de precios para servicio Taxi o normal
                if (empty($errores)) {
                    $precioBase = $this->reservaModelo->obtenerPrecioPorServicio($cuidador_id, $datos['servicio']);
                    $total = 0;

                    if ($esTaxi) {
                        // Calcula la distancia entre origen y destino
                        $distanciaKm = $this->calcularDistanciaKm($datos['origen'], $datos['destino']);
                        if ($distanciaKm === null) {
                            $errores[] = "No se pudo calcular la distancia para el servicio Taxi.";
                        } else {
                            $tarifaPorKm     = $precioBase;
                            $suplementoFijo  = 10.00;
                            $total           = $suplementoFijo + ($tarifaPorKm * $distanciaKm * $mascotasExtra);
                            $datos['distancia_km'] = $distanciaKm;
                        }
                    } else {
                        // Calcula el número de días de la reserva
                        $dias = calcularDiferenciaFechas($datos['fecha_inicio'], $datos['fecha_fin']);
                        if (!in_array($datos['servicio'], ['Alojamiento', 'Cuidado a domicilio']) && $dias > 0) {
                            $dias++;
                        }
                        $total = $precioBase * $mascotasExtra * $dias;
                    }
                }

                // Si no hay errores, crea la reserva y asocia las mascotas
                if (empty($errores)) {
                    $reserva_id = $this->reservaModelo->crearReserva($datos, $total);

                    foreach ($datos['mascotas'] as $mascotaId) {
                        $this->reservaModelo->asociarMascota($reserva_id, $mascotaId);
                    }

                    // Obtener datos del dueño y cuidador
                    $dueno = $this->duenoModelo->obtenerPerfilDueno($datos['duenio_id']);
                    $cuidador = $this->cuidadorModelo->obtenerPerfilCuidador($cuidador_id);

                    // Calcular subtotal, IVA y total
                    $subtotal = round($total / 1.21, 2);
                    $iva = round($total - $subtotal, 2);
                    $total_con_iva = $total;

                    // Crear la carpeta si no existe
                    $rutaFactura = RUTA_PUBLIC . "/facturas/00" . $datos['duenio_id'];
                    if (!file_exists($rutaFactura)) {
                        mkdir($rutaFactura, 0777, true);
                    }

                    // Nombre del archivo
                    $nombreArchivo = "$rutaFactura/INV$reserva_id.pdf";

                    // Crear PDF
                    // Inicializa el documento PDF con soporte UTF-8
                    $pdf = new TFPDF();
                    $pdf->AddPage();
                    $pdf->AddFont('DejaVu', '', 'DejaVuSansMono.ttf', true);
                    $pdf->AddFont('DejaVu-Bold', '', 'DejaVuSansMono-Bold.ttf', true);
                    $pdf->SetTextColor(0, 91, 92);

                    // Encabezado con la fecha y número de factura
                    $pdf->SetXY(10, 10);
                    $pdf->SetFont('DejaVu-Bold', '', 10);
                    $pdf->Cell(15, 5, 'Fecha:', 0, 0);
                    $pdf->SetFont('DejaVu', '', 10);
                    $pdf->Cell(0, 5,  date('d/m/Y'), 0, 1);
                    $pdf->SetFont('DejaVu-Bold', '', 10);
                    $pdf->Cell(25, 5, 'Factura Nº:', 0, 0);
                    $pdf->SetFont('DejaVu', '', 10);
                    $pdf->Cell(0, 5, 'INV' . $reserva_id, 0, 1);

                    // Logotipo y datos de la empresa
                    $pdf->Image(RUTA_PUBLIC . '/img/logo.png', 160, 10, 30);
                    $pdf->SetXY(160, 45);
                    $pdf->MultiCell(50, 5, "Guardería Patitas S.L.\nCIF: B12345678\nP.º del Prior, 97\n26004 Logroño, La Rioja", 0, 'L');

                    // Título principal
                    $pdf->SetFont('DejaVu-Bold', '', 16);
                    $pdf->SetXY(10, 25);
                    $pdf->Cell(0, 10, 'Factura de Reserva', 0, 1, 'C');

                    // Información del cliente
                    $pdf->SetXY(10, 45);
                    $pdf->SetFont('DejaVu-Bold', '', 10);
                    $pdf->Cell(20, 5, 'Dueño:', 0, 0);
                    $pdf->SetFont('DejaVu', '', 10);
                    $pdf->Cell(100, 5, $dueno->nombre, 0, 1);
                    $pdf->SetFont('DejaVu-Bold', '', 10);
                    $pdf->Cell(20, 5, 'Cuidador:', 0, 0);
                    $pdf->SetFont('DejaVu', '', 10);
                    $pdf->Cell(100, 5, $cuidador->nombre, 0, 1);

                    // Cabecera de los detalles de la reserva
                    $tipoCoste = getServicioTipoCoste($datos['servicio']);
                    $startX = 10;
                    $startY = 100;
                    $altoCelda = 6;
                    $anchosCols = [
                        'servicio' => 45,
                        'fechas' => 50,
                        'detalleCoste' => 25,
                        'mascotas' => 25,
                        'subtotal' => 25,
                    ];
                    $sumaPrimeras4 = $anchosCols['servicio'] + $anchosCols['fechas'] + $anchosCols['detalleCoste'] + $anchosCols['mascotas'];

                    // Encabezado de la tabla
                    $pdf->SetFont('DejaVu-Bold', '', 11);
                    $pdf->SetXY($startX, $startY);
                    $pdf->Cell($anchosCols['servicio'], $altoCelda, 'Servicio', 0, 0, 'L');
                    $pdf->Cell($anchosCols['fechas'], $altoCelda, 'Fechas de Servicio', 0, 0, 'L');
                    $pdf->Cell($anchosCols['detalleCoste'], $altoCelda, $tipoCoste, 0, 0, 'L');
                    $pdf->Cell($anchosCols['mascotas'], $altoCelda, 'Mascotas', 0, 0, 'L');
                    $pdf->Cell($anchosCols['subtotal'], $altoCelda, 'Coste', 0, 1, 'C');

                    // Línea divisoria
                    $pdf->Line($startX, $pdf->GetY(), $startX + array_sum($anchosCols), $pdf->GetY());
                    $pdf->Ln(2);

                    // Fila de datos
                    $pdf->SetFont('DejaVu', '', 10);
                    $pdf->SetX($startX);
                    $pdf->Cell($anchosCols['servicio'], $altoCelda, $datos['servicio'], 0, 0, 'L');
                    $fechaInicio = date('d/m/Y', strtotime($datos['fecha_inicio']));
                    $fechaFin = date('d/m/Y', strtotime($datos['fecha_fin']));
                    $fechas = ($datos['servicio'] === 'Taxi') ? $fechaInicio : "$fechaInicio - $fechaFin";
                    $pdf->Cell($anchosCols['fechas'], $altoCelda, $fechas, 0, 0, 'L');
                    $detalleCoste = ($tipoCoste === 'Kilómetros') ? number_format($datos['distancia_km'], 2) . ' km' : (isset($dias) ? $dias : '-');
                    $pdf->Cell($anchosCols['detalleCoste'], $altoCelda, $detalleCoste, 0, 0, 'C');
                    $numeroMascotas = count($datos['mascotas']);
                    $pdf->Cell($anchosCols['mascotas'], $altoCelda, $numeroMascotas, 0, 0, 'C');
                    $pdf->Cell($anchosCols['subtotal'], $altoCelda, number_format($subtotal, 2) . '€', 0, 1, 'R');

                    // Fila del IVA
                    $pdf->SetFont('DejaVu-Bold', '', 10);
                    $pdf->SetXY(($startX + $sumaPrimeras4) - 15, $pdf->GetY());
                    $pdf->Cell($anchosCols['subtotal'], $altoCelda, 'IVA 21%:', 0, 0, 'L');
                    $pdf->SetFont('DejaVu', '', 10);
                    $numText = number_format($iva, 2) . '€';
                    $anchoNum = $pdf->GetStringWidth($numText);
                    $pdf->SetXY($startX + $sumaPrimeras4 + $anchosCols['subtotal'] - ($anchoNum + 2), $pdf->GetY());
                    $pdf->Cell($anchoNum, $altoCelda, $numText, 0, 1, 'L');
                    $pdf->SetFont('DejaVu-Bold', '', 10);

                    // Fila del Total
                    $pdf->SetXY(($startX + $sumaPrimeras4) - 15, $pdf->GetY());
                    $pdf->Cell($anchosCols['subtotal'], $altoCelda, 'Total:', 0, 0, 'L');
                    $pdf->SetFont('DejaVu', '', 10);
                    $numText = number_format($total_con_iva, 2) . '€';
                    $anchoNum = $pdf->GetStringWidth($numText);
                    $pdf->SetXY($startX + $sumaPrimeras4 + $anchosCols['subtotal'] - ($anchoNum + 2), $pdf->GetY());
                    $pdf->Cell($anchoNum, $altoCelda, $numText, 0, 1, 'L');

                    // Mensaje final
                    $pdf->Ln(10);
                    $pdf->SetFont('DejaVu', '', 10);
                    $pdf->SetTextColor(100, 100, 100);
                    $pdf->Cell(0, 6, 'Gracias por confiar en Guardería Patitas. ¡Nos vemos pronto!', 0, 1, 'C');

                    // Guardar el archivo
                    $pdf->Output('F', $nombreArchivo);

                    $archivoPublicUrl = "/facturas/00{$datos['duenio_id']}/INV{$reserva_id}.pdf";

                    // Guardar factura en la base de datos
                    $this->duenoModelo->guardarFactura($reserva_id, $archivoPublicUrl);

                    //Enviar correo con la factura
                    $email = $dueno->email;
                    $asunto = "Factura INV{$reserva_id} Guardería Patitas";

                    // Obtener nombres de mascotas
                    $nombresMascotasArray = $this->mascotaModelo->nombresMascotas($datos['mascotas']);
                    // Extraer nombres y preparar un string
                    $nombresMascotas = implode(', ', array_map(function ($m) {
                        return htmlspecialchars($m->nombre);
                    }, $nombresMascotasArray));

                    $cuerpo = "
                    <p>Estimado/a <strong>{$dueno->nombre}</strong>,</p>
                    <p>Nos complace informarle que su reserva con el cuidador <strong>{$cuidador->nombre}</strong> ha sido confirmada con éxito.</p>
                    <p><strong>Servicio contratado:</strong> {$datos['servicio']}</p>
                    <p><strong>Fecha:</strong> {$fechas}</p>
                    <p><strong>Mascotas:</strong> {$nombresMascotas}</p>
                    <p><strong>Coste total:</strong> {$numText}</p>
                    <p>Adjunto a este correo encontrará la factura correspondiente a su reserva.</p>
                    <p>Le agradecemos la confianza depositada en <strong>Guardería Patitas</strong>. Si tiene cualquier consulta, no dude en responder a este mensaje.</p>
                    <br>
                    <p>Un cordial saludo,<br>
                    El equipo de Guardería Patitas</p>";
                    $adjunto = $nombreArchivo;

                    mandarCorreo($email, $asunto, $cuerpo, $adjunto);

                    redireccionar('/duenos/misReservas');
                }

                // Si hay errores, volver a mostrar la vista con los errores
                $cuidador = $this->cuidadorModelo->obtenerPerfilCuidador($cuidador_id);
                $servicios = $this->cuidadorModelo->obtenerServicios($cuidador_id);
                $mascotas = $this->reservaModelo->obtenerMascotas($_SESSION['usuario_id']);
                $reservas = $this->reservaModelo->obtenerReservasConfirmadas($cuidador_id);

                $precios = [];
                foreach ($servicios as $serv) {
                    $precios[$serv->servicio] = (float) $serv->precio;
                }

                $datos = [
                    'cuidador' => $cuidador,
                    'mascotas' => $mascotas,
                    'precios' => $precios,
                    'reservas' => $reservas,
                    'errores' => $errores
                ];

                $this->vista('reservas/crear', $datos);
            } else {
                // Mostrar formulario inicial
                $cuidador = $this->cuidadorModelo->obtenerPerfilCuidador($cuidador_id);
                $servicios = $this->cuidadorModelo->obtenerServicios($cuidador_id);
                $mascotas = $this->reservaModelo->obtenerMascotas($_SESSION['usuario_id']);
                $reservas = $this->reservaModelo->obtenerReservasConfirmadas($cuidador_id);

                $precios = [];
                foreach ($servicios as $serv) {
                    $precios[$serv->servicio] = (float) $serv->precio;
                }

                $datos = [
                    'cuidador' => $cuidador,
                    'mascotas' => $mascotas,
                    'precios' => $precios,
                    'reservas' => $reservas,
                    'errores' => []
                ];

                $this->vista('reservas/crear', $datos);
            }
        } else {
            redireccionar('/home');
        }
    }

    public function cancelar($reserva_id)
    {
        $reserva = $this->reservaModelo->obtenerReservaPorId($reserva_id);

        if ($_SESSION['grupo'] == 'dueno') {
            // Si el usuario es dueño, comprobar que la reserva pertenece al dueño
            if (!$reserva || $reserva->duenio_id != $_SESSION['usuario_id'] || comprobarFecha_Cancelacion($reserva->fecha_inicio)) {
                // Si la reserva no existe o no pertenece al dueño, redireccionar
                redireccionar('/duenos/misReservas');
            }
            // Cancelar la reserva
            $this->reservaModelo->cancelarReserva($reserva_id);

            redireccionar('/duenos/misReservas');
        } else {
            // Si el usuario no es dueño, redireccionar
            redireccionar('/autenticacion');
        }
    }

    public function rechazar($reserva_id)
    {
        $reserva = $this->reservaModelo->obtenerReservaPorId($reserva_id);

        if ($_SESSION['grupo'] == 'cuidador') {
            if (!$reserva || $reserva->cuidador_id != $_SESSION['usuario_id'] || comprobarFecha_Cancelacion($reserva->fecha_inicio)) {
                // Si la reserva no existe o no pertenece al cuidador, redireccionar
                redireccionar('/cuidadores/misReservas');
            }

            // Rechazar la reserva
            $this->reservaModelo->rechazarReserva($reserva_id);

            redireccionar('/cuidadores/misReservas');
        } else {
            // Si el usuario no es cuidador, redireccionar
            redireccionar('/autenticacion');
        }
    }

    public function completar($reserva_id)
    {
        $reserva = $this->reservaModelo->obtenerReservaPorId($reserva_id);

        if ($_SESSION['grupo'] == 'cuidador') {
            if (!$reserva || $reserva->cuidador_id != $_SESSION['usuario_id'] || comprobarFecha_Cancelacion($reserva->fecha_inicio)) {
                // Si la reserva no existe o no pertenece al cuidador, redireccionar
                redireccionar('/cuidadores/misReservas');
            }

            // Completar la reserva
            $this->reservaModelo->completarReserva($reserva_id);

            redireccionar('/cuidadores/misReservas');
        } else {
            // Si el usuario no es cuidador, redireccionar
            redireccionar('/autenticacion');
        }
    }

    // Calcula la distancia en kilómetros entre dos direcciones usando la API de OpenRouteService
    private function calcularDistanciaKm($origen, $destino)
    {
        // Geocodifica las direcciones para obtener coordenadas
        $coorOrigen = $this->geocodificar($origen);
        $coorDestino = $this->geocodificar($destino);

        // Si alguna coordenada no se pudo obtener, retorna null
        if (!$coorOrigen || !$coorDestino) {
            return null;
        }

        // Obtiene la API key de OpenRouteService desde las variables de entorno
        $apiKey = getenv('ORS_API_KEY');
        if (!$apiKey) {
            return null;
        }

        // Prepara el cuerpo de la petición con las coordenadas
        $body = [
            "coordinates" => [
                [(float)$coorOrigen['lon'], (float)$coorOrigen['lat']],
                [(float)$coorDestino['lon'], (float)$coorDestino['lat']]
            ]
        ];

        // Inicializa la petición cURL a la API de rutas
        $ch = curl_init("https://api.openrouteservice.org/v2/directions/driving-car/geojson");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($body)
        ]);

        // Ejecuta la petición y captura errores
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Si la respuesta es falsa, retorna null
        if ($response === false) {
            return null;
        }

        // Decodifica la respuesta JSON
        $data = json_decode($response, true);
        // Extrae la distancia en metros y la convierte a kilómetros
        if (isset($data['features'][0]['properties']['summary']['distance'])) {
            return $data['features'][0]['properties']['summary']['distance'] / 1000;
        }

        return null;
    }

    // Geocodifica una dirección usando Nominatim (OpenStreetMap) y retorna las coordenadas
    private function geocodificar($direccion)
    {
        if (empty($direccion)) {
            return null;
        }
        // Construye la URL de la petición
        $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($direccion);
        // Define el User-Agent para la petición HTTP
        $opts = [
            "http" => [
                "header" => "User-Agent: MiAppPHP/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        // Realiza la petición y obtiene el resultado JSON
        $json = @file_get_contents($url, false, $context);
        if (!$json) return null;
        $res = json_decode($json, true);
        // Retorna el primer resultado o null si no hay resultados
        return $res[0] ?? null;
    }
}
