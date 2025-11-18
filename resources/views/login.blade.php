<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>KeyStone-Login</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
  /* Reset default margin/padding */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  /* Full screen background and center container */
  body, html {
    height: 100%;
    background-color: #f1f1f1;
    font-family: Arial, sans-serif;
  }

  .container {
    min-height: 100vh; /* full height of viewport */
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px; /* padding for small screens */
  }

  .login-box {
    background-color: white;
    padding: 40px 30px;
    box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
    border-radius: 8px;
    width: 100%;
    max-width: 360px;
  }

  .logo {
    font-weight: bold;
    font-size: 28px;
    margin-bottom: 30px;
    text-align: center;
    user-select: none;
  }

  .logo .orange {
    color: #e67333; /* similar orange color */
  }

  form {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }

  input[type="text"],
  input[type="password"] {
    padding: 10px 15px;
    border: 1.8px solid #ddd;
    border-radius: 5px;
    font-size: 15px;
    transition: border-color 0.3s ease;
  }

  input[type="text"]:focus,
  input[type="password"]:focus {
    outline: none;
    border-color: #e67333;
  }

  button {
    padding: 12px 20px;
    background-color: #e67333;
    color: white;
    font-weight: 600;
    font-size: 16px;
    border: none;
    border-radius: 24px;
    cursor: pointer;
    transition: background-color 0.25s ease;
  }

  button:hover {
    background-color: #cc6229;
  }

  /* Responsive adjustments */
  @media (max-width: 400px) {
    .login-box {
      padding: 30px 20px;
      max-width: 100%;
      box-shadow: none;
      border-radius: 0;
    }
  }
</style>
</head>
<body>
  <div class="container">
    <div class="login-box" role="main" aria-labelledby="login-title">
      <h1 class="logo" id="login-title"><span class="orange">Key</span>stone</h1>
       <form method="POST" action="/login">
           @csrf
                 @error('name')
               <p class="text-danger">{{ $message }}</p>
           @enderror
              @error('password')
               <p class="text-danger">{{ $message }}</p>
           @enderror
        <input type="text" placeholder="Enter Username" name="name" aria-label="Username" value="{{ old('name') }}" />
     

        <input type="password" placeholder="Enter Password" name="password" aria-label="Password" />
        
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
