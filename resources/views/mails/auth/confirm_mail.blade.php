<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style type="text/css">
        /* Base Styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f7f7f7;
        }
        
        /* Email Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
        }
        
        /* Header */
        .header {
            padding: 30px 20px;
            text-align: center;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
        }
        
        .logo {
            max-width: 150px;
            height: auto;
        }
        
        /* Content */
        .content {
            padding: 30px 20px;
        }
        
        h1 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        p {
            margin-bottom: 20px;
            font-size: 16px;
            color: #555555;
        }
        
        /* Button */
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #6e8efb;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 20px 0;
        }
        
        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            background-color: #f5f5f5;
        }
        
        .social-links a {
            margin: 0 10px;
            text-decoration: none;
        }
        
        .social-icon {
            width: 24px;
            height: 24px;
        }
        
        /* Responsive */
        @media screen and (max-width: 480px) {
            .header {
                padding: 20px 15px;
            }
            
            .content {
                padding: 20px 15px;
            }
            
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="https://via.placeholder.com/150x50/ffffff/6e8efb?text=YourLogo" alt="Logo" class="logo">
            {{-- <h1>Bienvenue sur [Nom de votre App]</h1> --}}
        </div>
        
        <!-- Content -->
        <div class="content">
            <p>Bonjour {{ $user->username }},</p>
            
            <p>Merci pour votre inscription sur {{ config('app.name') }}. Nous sommes ravis de vous compter parmi nos utilisateurs.</p>
            
            <p>Voici votre code de confirmation :</p>
            <a class="button">{{ $code }}</a>
            
            <p>Veuillez entrer ce code dans l'application pour finaliser votre inscription.</p>
            <p>Ce code est valable pendant 10 minutes.</p>
            <p>Si vous n’êtes pas à l’origine de cette demande, vous pouvez ignorer cet e-mail.</p>
            
            
            <p>N’hésitez pas à nous contacter si vous avez besoin d’aide.</p>
            
            <p>Cordialement,<br>L'équipe  {{ config('app.name') }}</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="social-links">
                <a href="[Lien Twitter]"><img src="https://via.placeholder.com/24/999999/ffffff?text=TW" alt="Twitter" class="social-icon"></a>
                <a href="[Lien Facebook]"><img src="https://via.placeholder.com/24/999999/ffffff?text=FB" alt="Facebook" class="social-icon"></a>
                <a href="[Lien LinkedIn]"><img src="https://via.placeholder.com/24/999999/ffffff?text=IN" alt="LinkedIn" class="social-icon"></a>
            </div>
            <p>&copy; 2025 {{ config('app.name') }}. Tous droits réservés.</p>
            <p><a href="[Lien des préférences]" style="color: #999999;">Politiques de confidentialité</a></p>
            <p><small style="color: #888; margin-top: 20px;">Ceci est un message automatique, merci de ne pas y répondre.</small></p>
        </div>
    </div>
</body>
</html>