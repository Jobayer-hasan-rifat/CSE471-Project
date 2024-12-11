<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            color: #333;
        }

        header {
            background-color: #1e90ff;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        main {
            padding: 20px;
            text-align: center;
        }

        main p {
            font-size: 18px;
            line-height: 1.5;
        }

        .login-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #1e90ff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #104e8b;
        }
    </style>
</head>

<body>
    <header>
        <h1>REMS</h1>
    </header>
    <main>
        <p>Welcome to the Resource and Event Management System (REMS). This platform allows university clubs and
            organizations to seamlessly manage events, book venues, and communicate with the Office of Co-Curricular
            Activities (OCA). Log in to access your personalized dashboard and get started.</p>
        <a href="/livewire/forms/LoginForm.php" class="login-btn">Login</a>
    </main>
</body>

</html>
