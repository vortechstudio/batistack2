<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject ?? 'Message de ' . (App\Models\Core\Company::first()->name ?? config('app.name')) }}</title>
    <style>
        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8fafc;
        }

        /* Container principal */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Header avec logo */
        .email-header {
            background: linear-gradient(135deg, #787AF6 0%, #2b9cf2 100%);
            padding: 30px 40px;
            text-align: center;
            color: white;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo {
            max-height: 60px;
            max-width: 200px;
            height: auto;
        }

        .company-name {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .company-tagline {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
            font-weight: 300;
        }

        /* Corps du message */
        .email-body {
            padding: 40px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .content {
            font-size: 16px;
            line-height: 1.7;
            color: #4a5568;
            margin-bottom: 30px;
        }

        .content p {
            margin-bottom: 16px;
        }

        .content p:last-child {
            margin-bottom: 0;
        }

        /* Boutons d'action */
        .action-container {
            text-align: center;
            margin: 30px 0;
        }

        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #787AF6 0%, #2b9cf2 100%);
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(120, 122, 246, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(120, 122, 246, 0.4);
        }

        /* Section d'information */
        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #787AF6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-box h3 {
            color: #2d3748;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-box p {
            color: #4a5568;
            font-size: 14px;
            margin: 0;
        }

        /* Footer */
        .email-footer {
            background-color: #2d3748;
            color: #a0aec0;
            padding: 30px 40px;
            text-align: center;
            font-size: 14px;
        }

        .company-info {
            margin-bottom: 20px;
        }

        .company-info h4 {
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .contact-icon {
            width: 16px;
            height: 16px;
            opacity: 0.7;
        }

        .footer-links {
            border-top: 1px solid #4a5568;
            padding-top: 20px;
            margin-top: 20px;
        }

        .footer-links a {
            color: #a0aec0;
            text-decoration: none;
            margin: 0 10px;
            font-size: 13px;
        }

        .footer-links a:hover {
            color: #ffffff;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-header,
            .email-body,
            .email-footer {
                padding: 20px;
            }

            .company-name {
                font-size: 24px;
            }

            .contact-info {
                flex-direction: column;
                gap: 10px;
            }

            .btn-primary {
                display: block;
                margin: 0 auto;
                max-width: 250px;
            }
        }

        /* Styles pour les clients email */
        @media screen and (max-width: 480px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #1a202c;
            }

            .email-body {
                background-color: #1a202c;
            }

            .greeting {
                color: #e2e8f0;
            }

            .content {
                color: #cbd5e0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo-container">
                @if(App\Models\Core\Company::first()?->logo_path)
                    <img src="{{ asset('storage/' . App\Models\Core\Company::first()->logo_path) }}"
                         alt="{{ App\Models\Core\Company::first()->name ?? config('app.name') }}"
                         class="logo">
                @else
                    <h1 class="company-name">{{ App\Models\Core\Company::first()?->name ?? config('app.name') }}</h1>
                @endif
            </div>
            <p class="company-tagline">ERP Moderne pour le BTP</p>
        </div>

        <!-- Corps du message -->
        <div class="email-body">
            @if(isset($greeting))
                <div class="greeting">{{ $greeting }}</div>
            @endif

            <div class="content">
                {{ $slot }}
            </div>

            @if(isset($actionText) && isset($actionUrl))
                <div class="action-container">
                    <a href="{{ $actionUrl }}" class="btn-primary">{{ $actionText }}</a>
                </div>
            @endif

            @if(isset($additionalInfo))
                <div class="info-box">
                    <h3>Information importante</h3>
                    <p>{{ $additionalInfo }}</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="company-info">
                <h4>{{ App\Models\Core\Company::first()?->name ?? config('app.name') }}</h4>

                <div class="contact-info">
                    @if(App\Models\Core\Company::first()?->address)
                        <div class="contact-item">
                            <svg class="contact-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ App\Models\Core\Company::first()->address }}
                            @if(App\Models\Core\Company::first()->code_postal && App\Models\Core\Company::first()->ville)
                                , {{ App\Models\Core\Company::first()->code_postal }} {{ App\Models\Core\Company::first()->ville }}
                            @endif
                        </div>
                    @endif

                    @if(App\Models\Core\Company::first()?->phone)
                        <div class="contact-item">
                            <svg class="contact-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            {{ App\Models\Core\Company::first()->phone }}
                        </div>
                    @endif

                    @if(App\Models\Core\Company::first()?->email)
                        <div class="contact-item">
                            <svg class="contact-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            {{ App\Models\Core\Company::first()->email }}
                        </div>
                    @endif

                    @if(App\Models\Core\Company::first()?->web)
                        <div class="contact-item">
                            <svg class="contact-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                            </svg>
                            {{ App\Models\Core\Company::first()->web }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="footer-links">
                <a href="{{ config('app.url') }}">Accueil</a>
                <a href="{{ config('app.url') }}/contact">Contact</a>
                <a href="{{ config('app.url') }}/privacy">Confidentialité</a>
            </div>

            <p style="margin-top: 15px; font-size: 12px; opacity: 0.7;">
                © {{ date('Y') }} {{ App\Models\Core\Company::first()?->name ?? config('app.name') }}. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
