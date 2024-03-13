<?php

require_once("public/vendor/phpmailer/src/PHPMailer.php");
require_once("public/vendor/phpmailer/src/Exception.php");
require_once("public/vendor/phpmailer/src/SMTP.php");
require_once("public/phpqrcode/qrlib.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Login extends ControllerBase
{

  function __construct()
  {
    parent::__construct();
  }
  function render()
  {
    $this->view->render('login/index');
  }
  function acceso()
  {
    try {
      $user = LoginModel::user($_POST);
      if ($user != false && $user['usuario'] == $_POST['user-login-masivos']) {
        /* echo json_encode("Correcto usuario"); */
        if ($user['password_usuario'] == encrypt_decrypt('encrypt', $_POST['password-login-masivos'])) {
          /* echo json_encode("Correcto password"); */
          $_SESSION['id_usuario-' . constant('Sistema')] = $user['id_usuario'];
          $_SESSION['nombre_usuario-' . constant('Sistema')] = $user['nombre_usuario'];
          $_SESSION['usuario-' . constant('Sistema')] = $user['usuario'];
          $_SESSION['tipo_usuario-' . constant('Sistema')] = $user['tipo_usuario'];
          $data = [
            'estatus' => 'success',
            'titulo' => 'Bienvenido',
            'respuesta' => ''
          ];
        } else {
          $data = [
            'estatus' => 'error',
            'titulo' => 'Contraseña incorrecta',
            'respuesta' => 'La contraseña ingresada es incorrecta'
          ];
        }
      } else {
        $data = [
          'estatus' => 'error',
          'titulo' => 'Usuario incorrecto',
          'respuesta' => 'El usuario ingresado es incorrecto'
        ];
      }
    } catch (\Throwable $th) {
      echo "error controlador acceso: " . $th->getMessage();
      $data = [
        'estatus' => 'error',
        'titulo' => 'Error de servidor',
        'respuesta' => 'Contacte al área de sistemas'
      ];
    }
    echo json_encode($data);
  }
  function salir()
  {
    unset($_SESSION['id_usuario-' . constant('Sistema')]);
    unset($_SESSION['nombre_usuario-' . constant('Sistema')]);
    unset($_SESSION['usuario-' . constant('Sistema')]);
    unset($_SESSION['tipo_usuario-' . constant('Sistema')]);
    /* session_destroy(); */
    header("location:" . constant('URL'));
  }



  function guardarRegistro()
{
    try {
        if ($_POST['tipo'] == 'nuevo') {
            // Encriptar la contraseña antes de guardarla
            $_POST['password'] = encrypt_decrypt('encrypt', $_POST['password']);
            $resp = LoginModel::guardarRegistro($_POST);
        } else {
            echo ('noo');
        }
        if ($resp != false) {
            $data = [
                'estatus' => 'success',
                'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Usuario creado' : 'Usuario actualizado',
                'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creó correctamente el Usuario.' : 'Se actualizó correctamente el Usuario'
            ];
            if ($_POST['tipo'] == 'nuevo') {
                $datosCorreo = [
                    'nombre' => $_POST['nombre'],
                    'usuario' => $_POST['usuario'],
                    'correo' => $_POST['correo'],
                    'password' => encrypt_decrypt('decrypt', $_POST['password']),
                ];
                $this->enviarCartaIndividual($datosCorreo);
            }
        } else {
            $data = [
                'estatus' => 'warning',
                'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Usuario no creado' : 'Usuario no actualizado',
                'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente el Usuario.' : 'No se pudo actualizar correctamente el programa'
            ];
        }
    } catch (\Throwable $th) {
        $data = [
            'estatus' => 'error',
            'titulo' => 'Error servidor',
            'respuesta' => 'Contacte al área de sistemas. Error:' . $th->getMessage()
        ];
        return;
    }
    echo json_encode($data);
}



    // ###############################
    // #       CARTAS ENVIAR         #
    // #        BIBLIOTECA           #
    // ###############################
  function enviarCartaIndividual($datosCorreo)
  {
      $mail = new PHPMailer(true); // defaults to using php "mail()"
      $html = '
      <!DOCTYPE html>
      <html lang="en">
      <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Registro para Evento Médico</title>
      <link href="https://cdn.jsdelivr.net/npm/font-awesome@5.15.3/css/all.min.css" rel="stylesheet">
      <style>
      body {
                  font-family: Arial, sans-serif;
                  line-height: 1.6;
                  margin: 0;
                  padding: 0;
                  }
                  img {
                  max-width: 100%;
                  height: auto;
                  }
                  .container {
                  max-width: 600px;
                  margin: 0 auto;
                  padding: 20px;
                  }
                  .event-info {
                  border: 2px solid #244f84;
                  border-radius: 5px;
                  padding: 15px;
                  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                  margin-bottom: 20px;
                  }
                  h1 {
                  font-size: 28px;
                  margin-bottom: 20px;
                  color: #406dae;
                  text-align: center;
                  }
                  p {
                  margin-bottom: 15px;
                  text-align: justify;
                  }
                  ul {
                  margin-bottom: 15px;
                  padding-left: 20px;
                  }
                  li {
                  list-style: none;
                  margin-bottom: 10px;
                  }
                  i {
                  margin-right: 10px;
                  }
                  address {
                  font-style: normal;
                  margin-top: 30px;
                  }
                  .text-muted {
                  color: #888;
                  }
                  @media screen and (max-width: 600px) {
                  .container {
                      padding: 10px;
                  }
                  h1 {
                      font-size: 24px;
                  }
                  }
      </style>
      </head>
      <body>
      <div class="container">
        <br>
        <img src="' . constant("URL") . 'public/img/cintillo-correo.jpeg" alt="Cabezera del Correo">
        <br>
        <br>
        <div class="event-info">
        <h1>Confirmación de Registro en Biblioteca Virtual</h1>
        <p class="lead">Estimado(a) ' . $datosCorreo['nombre'] . '</p>
        <p>Bienvenido(a) a la Biblioteca Virtual. Hemos recibido con éxito tu registro.</p>
        <ul>
            <li><strong>Usuario:</strong> ' . $datosCorreo['usuario'] . '</li>
            <li><strong>Contraseña:</strong> ' . $datosCorreo['password'] . '</li>
            <li><strong>Correo Electrónico:</strong> ' . $datosCorreo['correo'] . '</li>
        </ul>
        <p>Ahora puedes iniciar sesión con tu usuario y contraseña en la Biblioteca Virtual. Si tienes alguna pregunta o comentario, estamos aquí para ayudarte.</p>
        <p>Saludos cordiales.</p>
        </div>
    </div>
      </body>
      </html>';
      
      try {
          $mail->IsSMTP();
          $mail->isHTML(true);
          $mail->SMTPDebug = 0;
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = "ssl";
          $mail->Host = 'mail.lahe.mx';
          $mail->Port = '465';
          $mail->Username = 'masivos@lahe.mx';
          $mail->Password = 'Masivos.129';
          $mail->SetFrom(trim('masivos@lahe.mx'), 'COLEGIO MEXICANO DE ORTOPEDIA Y TRAUMATOLOGÍA');
          $mail->AddAddress(trim($datosCorreo['correo']));
          $mail->Subject = 'Confirmacion de registro - ' . $datosCorreo['usuario'];
          $mail->Body = $html;
          $mail->AltBody = $html;
          $mail->CharSet = 'UTF-8';
          $mail->Encoding = 'base64';
  
          if ($mail->Send()) {
              return true;
          } else {
              return false;
          }
      } catch (phpmailerException $e) {
          echo "Error phpmailerexception:" . $e->errorMessage();
      } catch (Exception $e) {
          echo "Error Exception:" . $e->getMessage();
      }
  }
  





















}

?>