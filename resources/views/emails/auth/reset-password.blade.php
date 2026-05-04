<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de contraseña</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#0d1520;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f3f4f6;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background-color:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e5e7eb;">
                <tr>
                    <td style="background-color:#eaf8f8;padding:24px 28px 18px 28px;text-align:center;">
                        <img src="{{ url('/images/gamelink-logo.svg') }}" alt="GameLink" style="max-width:190px;height:auto;display:block;margin:0 auto 14px auto;">
                        <p style="margin:0;color:#007a7c;font-size:13px;font-weight:700;letter-spacing:0.03em;text-transform:uppercase;">No Reply · Seguridad de cuenta</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px;">
                        <h1 style="margin:0 0 14px 0;font-size:22px;line-height:1.3;color:#0d1520;">Restablece tu contraseña</h1>

                        <p style="margin:0 0 14px 0;font-size:15px;line-height:1.7;color:#334155;">
                            Hola {{ $user->name ?? 'usuario' }}, recibimos una solicitud para restablecer la contraseña de tu cuenta en GameLink.
                        </p>

                        <p style="margin:0 0 22px 0;font-size:15px;line-height:1.7;color:#334155;">
                            Haz clic en el botón para establecer una nueva contraseña. Este enlace caduca en {{ $expireMinutes }} minutos.
                        </p>

                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 22px 0;">
                            <tr>
                                <td>
                                    <a href="{{ $resetUrl }}" style="display:inline-block;background:#009194;color:#ffffff;text-decoration:none;font-weight:700;font-size:14px;line-height:1;padding:12px 18px;border-radius:10px;">
                                        Restablecer contraseña
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 8px 0;font-size:13px;line-height:1.6;color:#475569;">
                            Si no solicitaste este cambio, ignora este correo. Tu contraseña no se modificará.
                        </p>
                        <p style="margin:0;font-size:13px;line-height:1.6;color:#475569;">
                            Soporte: {{ $supportEmail }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
