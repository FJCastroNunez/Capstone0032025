<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cotizacion->id_cotizacion }}</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f8f9fa; font-family: Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08);">
                    <tr>
                        <td style="background-color: #0077b6; padding: 20px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; letter-spacing: 1px;">
                                🪟 Vidriería Verónica
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="color: #333333; font-size: 20px; margin-bottom: 10px;">
                                Hola {{ $cotizacion->datosCliente->nombre ?? 'Estimado cliente' }},
                            </h2>

                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">
                                Te enviamos la <strong>cotización #{{ $cotizacion->id_cotizacion }}</strong> correspondiente a tu solicitud.
                            </p>

                            <p style="color: #555555; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">
                                Puedes revisar los detalles en el documento adjunto (PDF) junto con los archivos correspondientes.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px; border-top: 1px solid #e9ecef; padding-top: 20px;">
                                <tr>
                                    <td style="font-size: 15px; color: #333333;">
                                        <strong>Total:</strong> ${{ number_format($cotizacion->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px; color: #333333;">
                                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            </table>

                            <p style="margin-top: 30px; font-size: 15px; color: #555555;">
                                Si tienes alguna duda, no dudes en contactarnos.<br>
                                📧 <a href="mailto:no-reply@vidrieriaveronica.cl" style="color: #0077b6;">contacto@vidrieriaveronica.cl</a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #f1f3f5; text-align: center; padding: 15px;">
                            <p style="color: #6c757d; font-size: 13px; margin: 0;">
                                © {{ date('Y') }} Vidriería Verónica — Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>