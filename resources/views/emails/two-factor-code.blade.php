<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Código de verificação</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f8fafc; padding:20px;">
    <div style="max-width:480px; margin:auto; background:white; padding:24px; border-radius:8px;">
        <h2 style="margin-top:0;">Código de verificação</h2>

        <p>Use o código abaixo para continuar seu login:</p>

        <div
            style="
                font-size:32px;
                font-weight:bold;
                letter-spacing:6px;
                text-align:center;
                margin:24px 0;
            "
        >
            {{ $code }}
        </div>

        <p style="color:#64748b; font-size:14px;">
            Este código expira em 10 minutos.
            <br>
            Se você não solicitou este acesso, ignore este e-mail.
        </p>
    </div>
</body>
</html>
