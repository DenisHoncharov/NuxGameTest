<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
<form action="/register" method="POST">
    @csrf
    <label for="username">Username</label>
    <input type="text" name="username" id="username" class="@error('username') is-invalid @enderror" required>
    @error('username')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <br>
    <label for="phone_number">Phonenumber</label>
    <input type="text" name="phone_number" id="phone_number" class="@error('phone_number') is-invalid @enderror" required>
    @error('phone_number')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <br>
    <button type="submit">Register</button>
</form>
</body>
</html>
