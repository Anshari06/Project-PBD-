<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="Image/png" sizes="42x42" href="{{ asset('img/logo-uner.png') }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <title>Login</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(30, 60, 114, 0.15);
        }

        .card-body {
            padding: 2.5rem 2rem;
        }

        .login-title {
            color: #1e3c72;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .form-label {
            color: #2a5298;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
        }

        .btn-link {
            color: #667eea;
            font-weight: 500;
        }

        .btn-link:hover {
            color: #2a5298;
            text-decoration: underline;
        }

        hr {
            border-top: 2px solid #667eea;
            opacity: 0.3;
        }
    </style>

</head>

<body class="">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    {{-- Login Container --}}
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="mb-4 text-center login-title">Login</h3>
                        <form>
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email"
                                    placeholder="email@example.com">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password"
                                    placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign in</button>
                        </form>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-link text-decoration-none">Sign up</a>
                            <a href="#" class="btn btn-link text-decoration-none">Forgot
                                password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
