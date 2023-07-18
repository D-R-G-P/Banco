<?php

require_once '../../app/db/user_session.php';
require_once '../../app/db/user.php';
require_once '../../app/db/db.php';

$user = new User();
$userSession = new UserSession();
$currentUser = $userSession->getCurrentUser();
$user->setUser($currentUser);

$db = new DB();
$pdo = $db->connect();

?>

<!DOCTYPE html>
<html lang="es-AR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>S.C.S. - Mi usuario</title>
   <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
   <link rel="stylesheet" href="/Banco/public/css/base.css">
   <link rel="stylesheet" href="/Banco/public/css/header.css">
   <link rel="stylesheet" href="/Banco/public/css/realizarPedido.css">

   <!-- FontAwesome -->
   <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>


   <link rel="stylesheet" type="text/css" href="/Banco/app/modules/select2/select2.min.css">

   <script src="/Banco/node_modules/jquery/dist/jquery.min.js"></script>

   <script src="/Banco/app/modules/select2/select2.min.js"></script>
</head>

<body>
   <header>
      <div class="logo">
         <a href="/Banco/"><i class="fa-solid fa-dolly"></i></a>
      </div>

      <div class="links">
         <a href="/Banco/">Inicio</a>
         <a href="/Banco/public/layouts/modificarStock.php">Modificar stock</a>
         <a href="/Banco/public/layouts/seguimientoSolicitudes.php" class="disabled">Seguimiento</a>
         <a href="/Banco/public/layouts/realizarPedido.php" class="disabled">Realizar pedido</a>
      </div>

      <button id="user" class="user BORON">
         <i id="userI" class="fa-solid fa-user BORON"></i>
         <i id="flecha" class="fa-solid fa-caret-down BORON"></i>
      </button>

      <div id="userOptions" class="userOptions BORON">
         <div class="datos">
            <div>
               Bienvenido/a <br>
               <?php echo $user->getNombre() . " " . $user->getApellido(); ?>
            </div>
            <div>
               Perfil: <br>
               <?php echo $user->getTipo_usuario() ?>
            </div>
            <div>
               Cargo: <br>
               <?php echo $user->getCargo() ?>
            </div>

         </div>
         <div class="botones">
            <a class="profile" href="/Banco/public/layouts/profile.php">Ir a mi perfil</a>
            <a style="color: red;" href="/Banco/app/db/logout.php"><i class="fa-solid fa-power-off"></i> Cerrar
               sesión</a>
         </div>
      </div>
   </header>

   <article>
      <div class="banco">
         Banco:
         <select name="banco" id="bancoSelect">
            <option value="" selected disabled>Seleccione una opción</option>
            <?php
            try {
               $stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
               $stmt->execute();

               $options = "";
               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $id_banco = $row['id'];
                  $banco = $row['banco'];
                  $siglas = $row['siglas'];
                  $options .= "<option value='$id_banco'>$banco - $siglas</option>";
               }

               // Escribir las opciones en el DOM
               echo $options;
            } catch (PDOException $e) {
               echo 'Error: ' . $e->getMessage();
            }
            ?>
         </select>
      </div>
      <hr>

      <div class="formContainer" id="formContainer"></div>




      <style>
         .solicitud {
            border: .2vw #6d6d6d solid;
            padding: 1vw;
            margin-top: 3vh;
            width: 80%;
         }

         .cabecera {
            height: 15vh;
            display: flex;
            flex-direction: row;
            align-content: center;
            justify-content: space-around;
            align-items: center;
         }

         .cabecera img {
            height: 15vh;
            width: auto;
         }

         .cabecera h1 {
            text-decoration: underline;
         }

         .datosPaciente {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
         }

         .samo {
            display: flex;
            margin-top: 5vw;
            border: .1vw #000 solid;
            height: max-content;
            flex-direction: column;
            flex-wrap: nowrap;
            align-content: center;
            justify-content: center;
            align-items: center;
            height: 50vw;
         }
      </style>

      <div class="solicitud">
         <form action="">
            <div class="cabecera">
               <img src="/Banco/public/image/higaLogo.png" alt="H.I.G.A. General San Martín - Logo">
               <h1>Solicitud de autorización para la compra de prótesis</h1>
            </div>
            <div class="datosPaciente">
               <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                  <div>
                     <label for="paciente">Paciente:</label>
                     <input type="text" name="paciente" id="paciente" required>
                  </div>

                  <div>
                     <label for="fecha">Fecha:</label>
                     <input type="date" name="fecha" id="fecha" required>
                  </div>
               </div>

               <div>
                  <label for="edad">Edad:</label>
                  <input type="number" name="edad" id="edad" required>
               </div>

               <div>
                  <label for="documento">Documento:</label>
                  <input type="text" name="documento" id="documento" required>
               </div>
            </div>
            <div class="datosCirugia" style="width: 100%;">
               <div style="width: 100%;">
                  <label for="controlBuscador">Diagnostico:</label>

                  <select id="controlBuscador" style="width: 100%;">
                     <?php
                     try {
                        $stmt = $pdo->prepare("SELECT clave, descripcion FROM categoriascie10");
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                           $clave = $row['clave'];
                           $descripcion = $row['descripcion'];
                           echo '<option value="' . $clave . ' - ' . $descripcion . '">' . $clave . ' - ' . $descripcion . '</option>';
                        }
                     } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                     }
                     ?>
                  </select>

                  <label for="controlBuscadorSecond">Nomenclador y cirugía:</label>

                  <select id="controlBuscadorSecond" style="width: 100%;">
                     <?php
                     try {
                        $stmt = $pdo->prepare("SELECT codigo, descripcion FROM nomencladorescx");
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                           $codigo = $row['codigo'];
                           $descripcion = $row['descripcion'];
                           echo '<option value="' . $codigo . ' - ' . $descripcion . '">' . $codigo . ' - ' . $descripcion . '</option>';
                        }
                     } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                     }
                     ?>
                  </select>
               </div>
            </div>
            <div class="material">
               <table>
                  <thead>
                     <tr>
                        <th>Item</th>
                        <th>Nombre</th>
                        <th>Descripcion corta</th>
                        <th>Descripcion adicional</th>
                        <th>Cantidad a solicitar</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     try {
                        $stmt = $pdo->prepare("SELECT item, nombre, d_corta, d_larga FROM items WHERE banco = 'CIGE' ORDER BY item ASC;
                        ");
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                           $item = $row['item'];
                           $nombre = $row['nombre'];
                           $d_corta = $row['d_corta'];
                           $d_larga = $row['d_larga'];

                           echo '<tr>';
                           echo '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
                           echo '<td style="vertical-align: middle;">' . $nombre . '</td>';
                           echo '<td style="vertical-align: middle;">' . $d_corta . '</td>';
                           echo '<td style="vertical-align: middle;">' . $d_larga . '</td>';
                           echo '<td style="vertical-align: middle;"><input type="number"></td>';
                           echo '</tr>';
                        }
                     } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                     }
                     ?>
                  </tbody>
               </table>
               <input type="hidden" value="<?php echo $user->getDni(); ?>">
            </div>

            <div class="samo">
               <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ORDEN DE PRESTACIÓN</p>
               <p>&nbsp;</p>
               <table width="726">
                  <tbody>
                     <tr>
                        <td colspan="11" width="614">
                           <p>1. Establecimiento</p>
                        </td>
                        <td width="112">
                           <p>1.1. Código</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="11" width="614">
                           <p>2. Apellidos y Nombres</p>
                        </td>
                        <td width="112">
                           <p>3. Fecha</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="11" width="614">
                           <p>2.1. Domicilio&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Localidad</p>
                        </td>
                        <td width="112">
                           <p>&nbsp;</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td width="93">
                           <p>4. Sexo</p>
                        </td>
                        <td colspan="2" width="58">
                           <p>5. Edad</p>
                        </td>
                        <td width="116">
                           <p>6. N. de Documento</p>
                        </td>
                        <td width="85">
                           <p>7. Tipo de Doc.</p>
                        </td>
                        <td colspan="2" width="62">
                           <p>8. C.E.</p>
                        </td>
                        <td width="66">
                           <p>9. Sala</p>
                        </td>
                        <td colspan="2" width="66">
                           <p>10. Cama</p>
                        </td>
                        <td width="68">
                           <p>11. H.CL.N.</p>
                           <p>&nbsp;</p>
                        </td>
                        <td width="112">
                           <p>&nbsp;</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="2" width="126">
                           <p>12. Condición</p>
                           <p>&nbsp;</p>
                        </td>
                        <td colspan="7" width="387">
                           <p>13. Obra Social o Mutual</p>
                           <p>&nbsp;</p>
                        </td>
                        <td colspan="2" width="101">
                           <p>14. Tipo Afil.</p>
                        </td>
                        <td width="112">
                           <p>15. N. de Afiliado</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="11" width="614">
                           <p>16. Diagnóstico Clínico</p>
                        </td>
                        <td width="112">
                           <p>17. Código</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="2" width="126">
                           <p>18. Código</p>
                        </td>
                        <td colspan="7" width="387">
                           <p>19. Concepto</p>
                           <p>&nbsp;</p>
                        </td>
                        <td colspan="2" width="101">
                           <p>20. Unitario</p>
                        </td>
                        <td width="112">
                           <p>21. Total</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="2" width="126">
                           <p>22. Factura Interv.</p>
                        </td>
                        <td colspan="4" rowspan="3" width="250">
                           <p>23. Firma Profesional&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sello y Matrícula</p>
                        </td>
                        <td colspan="5" rowspan="3" width="238">
                           <p>25. Conforme Afiliado</p>
                        </td>
                        <td width="112">
                           <p>24. Total</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="2" rowspan="2" width="126">
                           <p>&nbsp;</p>
                        </td>
                        <td width="112">
                           <p>26. Abonar por Af.</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td rowspan="2" width="112">
                           <p>26.1. Abonar por O.S.</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td colspan="6" rowspan="2" width="376">
                           <p>27. Autorización O.S. Internación o Práctica</p>
                           <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Firma y Sello Aclaratorio&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sello Delegación</p>
                        </td>
                        <td colspan="5" rowspan="2" width="238">
                           <p>28. Práct. Realizada Por</p>
                           <p>Firma Profesional&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sello y Matrícula</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                     <tr>
                        <td width="112">
                           <p>&nbsp;</p>
                        </td>
                        <td width="0">&nbsp;</td>
                     </tr>
                  </tbody>
               </table>
            </div>





            <script>
               $(document).ready(function() {
                  $('#controlBuscador').select2();
                  $('#controlBuscadorSecond').select2();
               });
            </script>
         </form>
      </div>

   </article>

   <footer>
      &copy; Dirección de Redes y Gestión de Personas. Todos los derechos reservados
   </footer>
</body>
<script src="/Banco/public/js/header.js"></script>
<script src="/Banco/public/js/realizarPedido.js"></script>

</html>