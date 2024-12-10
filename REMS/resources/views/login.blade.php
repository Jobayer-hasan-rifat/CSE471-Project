<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - REMS</title>
        <style>
            body {
                background-color: white;
                color: black;
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .login-container {
                background-color: blue;
                color: white;
                padding: 20px;
                border-radius: 8px;
                width: 300px;
                text-align: center;
            }
            input {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: none;
                border-radius: 4px;
            }
            button {
                background-color: white;
                color: blue;
                padding: 10px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            button:hover {
                background-color: lightgray;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>Login to REMS</h2>
            <form action="#" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
</html>