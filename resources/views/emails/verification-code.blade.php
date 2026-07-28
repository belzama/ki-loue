<!-- resources/views/emails/verification-code.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 500px; margin: 40px auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #2d6a4f; padding: 30px; text-align: center; }
        .header h1 { color: white; margin: 0; font-size: 22px; }
        .body { padding: 30px; text-align: center; }
        .code { font-size: 42px; font-weight: bold; letter-spacing: 10px; color: #2d6a4f; background: #f0faf4; padding: 20px 30px; border-radius: 10px; display: inline-block; margin: 20px 0; border: 2px dashed #2d6a4f; }
        .warning { color: #e74c3c; font-size: 13px; margin-top: 15px; }
        .footer { text-align: center; color: #aaa; font-size: 12px; padding: 15px; background: #f9f9f9; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🔐 Vérification de votre email</h1>
    </div>
    <div class="body">
        <p>Bonjour,</p>
        <p>Voici votre code de vérification :</p>
        <div class="code">{{ $code }}</div>
        <p>Ce code est valable <strong>10 minutes</strong>.</p>
        <p class="warning">⚠️ Ne partagez ce code avec personne.</p>
        <p style="color: #6c757d; font-size: 0.85rem;">
            Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.
        </p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
</div>
</body>
</html>