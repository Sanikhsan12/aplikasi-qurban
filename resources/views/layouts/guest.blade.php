<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                /* Colors from styles.css */
                --primary: hsl(158, 64%, 32%);
                --primary-light: hsl(158, 50%, 45%);
                --primary-dark: hsl(158, 70%, 22%);
                --primary-foreground: hsl(45, 30%, 97%);
                
                --gold: hsl(43, 74%, 49%);
                --gold-light: hsl(43, 80%, 65%);
                --gold-dark: hsl(43, 70%, 35%);
                
                --background: hsl(45, 30%, 97%);
                --foreground: hsl(150, 30%, 15%);
                
                --card: hsl(0, 0%, 100%);
                --border: hsl(150, 20%, 85%);
                --muted-foreground: hsl(150, 10%, 45%);
                
                --shadow-card: 0 8px 30px -8px hsla(150, 30%, 15%, 0.12);
                --radius: 0.75rem;
                --radius-lg: 1rem;
            }

            body {
                font-family: 'Inter', sans-serif;
            }
            
            .auth-container {
                min-height: 100vh;
                background: var(--background);
                position: relative;
                overflow: hidden;
            }
            
            .auth-decoration {
                position: absolute;
                border-radius: 50%;
                opacity: 0.05;
            }
            
            .decoration-1 {
                width: 300px;
                height: 300px;
                background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
                top: -150px;
                right: -150px;
            }
            
            .decoration-2 {
                width: 200px;
                height: 200px;
                background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
                bottom: -100px;
                left: -100px;
            }
            
            .decoration-3 {
                width: 150px;
                height: 150px;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
                top: 50%;
                left: 10%;
            }
            
            .auth-card {
                background: var(--card);
                border-radius: var(--radius-lg);
                box-shadow: var(--shadow-card);
                overflow: hidden;
                border: 1px solid var(--border);
            }
            
            .auth-header {
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                padding: 2rem;
                text-align: center;
                position: relative;
            }
            
            .auth-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
            }
            
            .logo-container {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1rem;
            }
            
            .logo-icon {
                width: 40px;
                height: 40px;
                background: var(--primary);
                border-radius: var(--radius);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary-foreground);
            }
            
            .logo-text {
                font-family: 'Playfair Display', serif;
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--primary-foreground);
            }
            
            .logo-text .text-gold {
                background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .auth-title {
                font-family: 'Playfair Display', serif;
                font-size: 1.875rem;
                font-weight: 700;
                color: var(--primary-foreground);
                margin-bottom: 0.5rem;
            }
            
            .auth-subtitle {
                color: rgba(255, 255, 255, 0.7);
                font-size: 0.875rem;
            }
            
            .auth-body {
                padding: 2.5rem;
            }
            
            .form-group {
                margin-bottom: 1.5rem;
            }
            
            .form-label {
                display: block;
                font-size: 0.875rem;
                font-weight: 600;
                color: var(--foreground);
                margin-bottom: 0.5rem;
            }
            
            .form-input {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid var(--border);
                border-radius: var(--radius);
                font-size: 0.875rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                background: var(--card);
                color: var(--foreground);
            }
            
            .form-input:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px hsla(158, 64%, 32%, 0.1);
            }
            
            .form-textarea {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid var(--border);
                border-radius: var(--radius);
                font-size: 0.875rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                background: var(--card);
                color: var(--foreground);
                resize: vertical;
            }
            
            .form-textarea:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px hsla(158, 64%, 32%, 0.1);
            }
            
            .btn-primary {
                width: 100%;
                padding: 0.875rem 1.5rem;
                background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
                border: none;
                border-radius: var(--radius);
                color: var(--foreground);
                font-weight: 600;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 20px -4px hsla(43, 74%, 49%, 0.3);
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 30px -8px hsla(43, 74%, 49%, 0.4);
            }
            
            .link-text {
                color: var(--primary);
                text-decoration: none;
                font-weight: 500;
                font-size: 0.875rem;
                transition: color 0.3s ease;
            }
            
            .link-text:hover {
                color: var(--primary-dark);
                text-decoration: underline;
            }
            
            .checkbox-container {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            .checkbox-input {
                width: 1.125rem;
                height: 1.125rem;
                border: 2px solid var(--border);
                border-radius: 4px;
                cursor: pointer;
                accent-color: var(--primary);
            }
            
            .divider {
                text-align: center;
                margin: 1.5rem 0;
                color: var(--muted-foreground);
                font-size: 0.875rem;
            }
            
            .alert-success {
                background: hsla(158, 64%, 32%, 0.1);
                border: 1px solid var(--primary-light);
                color: var(--primary-dark);
                padding: 0.75rem 1rem;
                border-radius: var(--radius);
                font-size: 0.875rem;
                margin-bottom: 1rem;
            }
            
            .alert-info {
                background: hsla(158, 50%, 45%, 0.1);
                border: 1px solid var(--primary-light);
                color: var(--primary);
                padding: 0.75rem 1rem;
                border-radius: var(--radius);
                font-size: 0.875rem;
                margin-bottom: 1rem;
            }
            
            .error-text {
                color: hsl(0, 84%, 60%);
                font-size: 0.75rem;
                margin-top: 0.25rem;
            }
            
            @media (max-width: 640px) {
                .auth-body {
                    padding: 1.5rem;
                }
                
                .auth-header {
                    padding: 1.5rem;
                }
                
                .auth-title {
                    font-size: 1.5rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <!-- Decorative Elements -->
            <div class="auth-decoration decoration-1"></div>
            <div class="auth-decoration decoration-2"></div>
            <div class="auth-decoration decoration-3"></div>
            
            <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; position: relative; z-index: 10;">
                <div style="width: 100%; max-width: 28rem;">
                    <div class="auth-card">
                        <div class="auth-header">
                            <div class="logo-container">
                                <div class="logo-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                                    </svg>
                                </div>
                                <div class="logo-text">
                                    Manajemen <span class="text-gold">Kurban</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="auth-body">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>