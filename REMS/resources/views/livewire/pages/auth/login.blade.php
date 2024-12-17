<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REMS</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f8f7ff;
        }

        .container {
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }

        .logo {
            color: #5b49d6;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #666;
            margin-bottom: 3rem;
        }

        .login-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .welcome-text {
            color: #5b49d6;
            font-size: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .btn-signin {
            width: 100%;
            padding: 0.75rem;
            background-color: #5b49d6;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn-signin:hover {
            background-color: #4a3ac4;
        }

        .help-text {
            margin-top: 1rem;
            color: #5b49d6;
            text-decoration: none;
        }

        .help-text:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 2rem;
            color: #666;
            font-size: 0.9rem;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">REMS</div>
        <div class="subtitle">Streamline your club management</div>
        
        <div class="login-card">
            <div class="welcome-text">Welcome Back!</div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>Select Your Organization</label>
                    <select name="email" class="form-control" required>
                        <option value="">Select organization</option>
                        <option value="oca@bracu.ac.bd">OCA</option>
                        <option value="bucc@bracu.ac.bd">BUCC</option>
                        <option value="robu@bracu.ac.bd">ROBU</option>
                        <option value="buedf@bracu.ac.bd">BUEDF</option>
                        <option value="buac@bracu.ac.bd">BUAC</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn-signin">Sign In</button>
            </form>

            <a href="#" class="help-text">Need help?</a>
        </div>

        <div class="footer">
            2024 REMS. All rights reserved.
        </div>
    </div>
</body>
</html>
