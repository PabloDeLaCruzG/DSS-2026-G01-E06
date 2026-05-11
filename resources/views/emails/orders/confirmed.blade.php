<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido confirmado</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#0d1520;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f3f4f6;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background-color:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e5e7eb;">
                <tr>
                    <td style="background-color:#eaf8f8;padding:24px 28px 18px 28px;text-align:center;">
                        <img src="{{ url('/images/gamelink-logo.svg') }}" alt="GameLink" style="max-width:190px;height:auto;display:block;margin:0 auto 14px auto;">
                        <p style="margin:0;color:#007a7c;font-size:13px;font-weight:700;letter-spacing:0.03em;text-transform:uppercase;">No Reply · Pedido confirmado</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px;">
                        <h1 style="margin:0 0 14px 0;font-size:22px;line-height:1.3;color:#0d1520;">Tu pedido está confirmado</h1>

                        <p style="margin:0 0 14px 0;font-size:15px;line-height:1.7;color:#334155;">
                            Hola {{ $user->name ?? 'usuario' }}, tu pago se ha procesado correctamente.
                        </p>

                        <p style="margin:0 0 6px 0;font-size:14px;line-height:1.6;color:#334155;">
                            <strong>Pedido:</strong> #{{ $order->id }}
                        </p>
                        <p style="margin:0 0 16px 0;font-size:14px;line-height:1.6;color:#334155;">
                            <strong>Total:</strong> {{ number_format($order->total_amount, 2) }} €
                        </p>

                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 18px 0;border-collapse:collapse;">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#334155;">
                                        {{ $item->gameAd?->game?->title ?? 'Artículo' }}
                                    </td>
                                    <td align="right" style="padding:8px 0;border-bottom:1px solid #e5e7eb;font-size:14px;color:#334155;white-space:nowrap;">
                                        {{ number_format($item->unit_price, 2) }} €
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 22px 0;">
                            <tr>
                                <td>
                                    <a href="{{ $ordersUrl }}" style="display:inline-block;background:#009194;color:#ffffff;text-decoration:none;font-weight:700;font-size:14px;line-height:1;padding:12px 18px;border-radius:10px;">
                                        Ver mis pedidos
                                    </a>
                                </td>
                            </tr>
                        </table>

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
