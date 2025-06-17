<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        input { padding: 8px; width: 200px; }
        button { padding: 8px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Register</h2>
    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label>Name:</label><br>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email:</label><br>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password:</label><br>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
</body>
</html> 