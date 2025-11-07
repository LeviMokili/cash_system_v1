<!DOCTYPE html>
<html>
<head>
    <title>User2 Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">User2 Dashboard</a>
            <div class="navbar-nav ms-auto">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Welcome to User2 Dashboard</h1>
        <p>You are logged in as <strong>User2</strong></p>
        <!-- Add user2-specific content here -->
    </div>
</body>
</html>