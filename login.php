<!DOCTYPE html>
<html lang="es-AR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SCS - Login</title>
  <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
  <link rel="stylesheet" href="/Banco/public/css/base.css">
  <link rel="stylesheet" href="/Banco/public/css/login.css">

  <!-- FontAwesome -->
  <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>

</head>

<body>
  <div class="container">
    <i class="fa-solid fa-dolly iconoLogo"></i>
    <div class="wrapper">
      <div class="title">
        <span>Iniciar sesión</span>
        <p>S.C.S. de Banco</p>
      </div>
      <p id="malLogin"><?php echo $errorLogin ?></p>
      <form method="post">
        <div class="row">
          <div class="icon">
            <i class="fa-solid fa-user"></i>
          </div>
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="row">
          <div class="icon">
            <i class="fas fa-lock icono"></i>
          </div>
          <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="row button">
          <input type="submit" value="Iniciar sesión">
        </div>
      </form>
    </div>
  </div>
</body>

</html>