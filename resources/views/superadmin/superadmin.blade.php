<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Super Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #22c55e;
            --warning: #facc15;
            --danger: #ef4444;
            --info: #38bdf8;
            --purple: #8b5cf6;
            --teal: #14b8a6;
            --bg-light: #f8fafc;
            --bg-dark: #1e293b;
            --text-dark: #1e293b;
            --text-muted: #666;
            --shadow: 0 6px 24px rgba(0,0,0,0.1);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        [data-theme="dark"] {
            --bg-light: #1e293b;
            --bg-dark: #111827;
            --text-dark: #f1f5f9;
            --text-muted: #9ca3af;
        }
        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            transition: var(--transition);
            overflow-x: hidden;
        }
        @yield('styles')
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @yield('sidebar')
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>