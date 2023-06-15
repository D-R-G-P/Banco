<!DOCTYPE html>
<html lang="es-AR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SCS - Login</title>
  <link rel="shortcut icon" href="/GDC/public/image/GDC.ico" type="image/x-icon">
  <link rel="stylesheet" href="/GDC/public/css/login.css">

  <!-- FontAwesome -->
  <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>

</head>

<body>
  <div class="container">
    <img src="/GDC/public/image/GDC.png" alt="Logo de Gestión de Camas">
    <div class="wrapper">
      <div class="title">
        <span>Iniciar sesión</span>
        <p>S.A.I. de G.D.C.</p>
      </div>
      <p id="malLogin"><?php echo $errorLogin ?></p>
      <form method="post">
        <div class="row">
          <div class="icon">
            <i class="fa-solid fa-user icono"></i>
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