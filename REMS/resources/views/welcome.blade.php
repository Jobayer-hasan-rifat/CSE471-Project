<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>REMS</title>

        <!-- Styles -->
        <style>
            body {
                background-color: white;
                color: black;
                font-family: Arial, sans-serif;
            }
            .header {
                background-color: blue;
                color: white;
                padding: 20px;
                text-align: center;
            }
            .login {
                float: right;
                margin-top: -40px;
            }
            .content {
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1><strong>REMS</strong></h1>
            <div class="login">
                <a href="{{ route('login') }}"><button>Login</button></a>
            </div>
        </div>
        <div class="content">
            <h2>Welcome to REMS</h2>
            <p>REMS is a platform for managing co-curricular activities at BRAC University.</p>
            <p>Login to access the features of REMS.</p>
        </div>
    </body>
</html>
