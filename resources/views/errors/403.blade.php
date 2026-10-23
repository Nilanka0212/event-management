<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden | Event Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 48px 36px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .error-icon {
            width: 80px;
            height: 80px;
            background: #fef2f2;
            color: #dc2626;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin-bottom: 24px;
            border: 1px solid #fecaca;
        }
        .error-code {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #dc2626;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .error-title {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .error-desc {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .btn-home {
            background: linear-gradient(135deg, #484646 0%, #3a3838 100%);
            border: 1px solid #3a3838;
            color: #ffffff;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-home:hover {
            background: #252424;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(58, 56, 56, 0.25);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div class="error-code">403 Forbidden</div>
        <h1 class="error-title">Access Restricted</h1>
        <p class="error-desc">
            You do not have the required administrative permissions to access this page. Please contact your system administrator if you believe this is an error.
        </p>
        <a href="{{ route('dashboard') }}" class="btn-home">
            <i class="bi bi-arrow-left"></i> Return to Dashboard
        </a>
    </div>
</body>
</html>
