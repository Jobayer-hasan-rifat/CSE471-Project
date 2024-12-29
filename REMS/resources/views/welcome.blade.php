<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'REMS') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            color: #1a202c;
        }
        .container {
            min-height: 100vh;
            background: linear-gradient(135deg, #EEF2FF 0%, #ffffff 100%);
        }
        .nav {
            background: rgba(255, 255, 255, 0.9);
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4f46e5;
            text-decoration: none;
        }
        .nav-link {
            color: #4b5563;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .nav-link:hover {
            color: #4f46e5;
        }
        .hero {
            max-width: 1200px;
            margin: 0 auto;
            padding: 6rem 1rem;
            text-align: center;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
            color: #1a202c;
            margin-bottom: 1.5rem;
        }
        .hero h1 span {
            color: #4f46e5;
            display: block;
            margin-top: 0.5rem;
        }
        .hero p {
            max-width: 600px;
            margin: 0 auto;
            color: #4b5563;
            font-size: 1.125rem;
            margin-bottom: 2rem;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #4338ca;
        }
        .features {
            background: white;
            padding: 4rem 1rem;
        }
        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 0 1rem;
        }
        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .feature-card h3 {
            color: #1a202c;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
        .feature-card p {
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="nav">
            <div class="nav-content">
                <a href="/" class="logo">REMS</a>
                <div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Log in</a>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="hero">
            <h1>
                Reservation and Event
                <span>Management System</span>
            </h1>
            <p>
                Streamline your university club's event planning and resource management with our comprehensive system.
            </p>
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="button">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="button">Get Started</a>
                @endauth
            </div>
        </div>

        <div class="features">
            <div class="features-grid">
                <div class="feature-card">
                    <h3>Event Planning</h3>
                    <p>Easily plan and schedule your club events with our intuitive interface.</p>
                </div>

                <div class="feature-card">
                    <h3>Resource Management</h3>
                    <p>Efficiently manage venues and resources for your events.</p>
                </div>

                <div class="feature-card">
                    <h3>Approval System</h3>
                    <p>Streamlined approval process for event requests.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
