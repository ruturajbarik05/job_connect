<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; color: #334155; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .container { text-align: center; padding: 2rem; }
        .code { font-size: 8rem; font-weight: 800; color: #f59e0b; line-height: 1; }
        h1 { font-size: 1.5rem; margin: 1rem 0 0.5rem; color: #1e293b; }
        p { color: #64748b; margin-bottom: 2rem; }
        a { display: inline-block; padding: 0.75rem 2rem; background: #3b82f6; color: #fff; text-decoration: none; border-radius: 0.5rem; font-weight: 500; transition: background 0.2s; }
        a:hover { background: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="code">404</div>
        <h1>Page Not Found</h1>
        <p>The page you are looking for doesn't exist or has been moved.</p>
        <a href="{{ url('/') }}">Go Home</a>
    </div>
</body>
</html>
