<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Dashboard</title>
</head>

<body class="d-flex" style="min-height: 100vh; overflow: hidden;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    {{-- Sidebar --}}
    <x-sidebar></x-sidebar>

    {{-- Main Content Area --}}
    <main class="flex-grow-1 d-flex flex-column" style="height: 100vh; overflow: hidden;">
        {{-- Header Fixed --}}
        <x-header>Manage Users</x-header>

        {{-- Content Area with Scroll --}}
        <div class="flex-grow-1" style="overflow-y: auto;">

            <div class="container-fluid p-3">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h2 class="mb-0">User Management</h2>
                            <p class="text-muted small mb-0">Here you can manage users of the
                                system.</p>
                        </div>
                    </div>

                    <!-- Inline Add User Form (polished) -->
                    <div class="card mb-3">
                        <div class="card-header py-2">
                            <strong class="small mb-0">Add User</strong>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('/manage_user') }}" method="POST"
                                class="row g-2 align-items-end">
                                @csrf
                                <div class="col-md-4">
                                    <label for="username" class="visually-hidden">Username</label>
                                    <input type="text" name="username" id="username"
                                        class="form-control form-control-sm" placeholder="Username"
                                        required autofocus>
                                </div>
                                <div class="col-md-4">
                                    <label for="password" class="visually-hidden">Password</label>
                                    <input type="password" name="password" id="password"
                                        class="form-control form-control-sm" placeholder="Password"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label for="idrole" class="visually-hidden">Role ID</label>
                                    <input type="number" name="idrole" id="idrole"
                                        class="form-control form-control-sm" placeholder="Role ID">
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Add User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:70px">Number</th>
                                        <th style="width:70px">ID</th>
                                        <th>Name</th>
                                        <th>Password</th>
                                        <th>Role</th>
                                        <th style="width:180px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->iduser }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->password }}</td>
                                            <td>{{ $user->nama_role ?? '-' }}</td>
                                            <td>
                                                <a href="{{ url('/manage_user/' . $user->iduser . '/edit') }}"
                                                    class="btn btn-sm btn-warning me-1">Edit</a>
                                                <a href="{{ url('/manage_user/' . $user->iduser) }}"
                                                    class="btn btn-sm btn-info me-1">View</a>
                                                <form
                                                    action="{{ url('/manage_user/' . $user->iduser) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus user ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No users found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
