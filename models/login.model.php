<?php
/**
 *
 */
class LoginModel extends ModelBase
{
    public function __construct()
    {
        parent::__construct();
    }
    public static function user($datos){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_usuarios WHERE usuario = :usuario");
            $query->execute([
                ':usuario' => $datos['user-login-masivos']
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model user: ".$e->getMessage();
            return;
        }
    }






    public static function guardarRegistro($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_usuarios (id_usuario, nombre_usuario, usuario, password_usuario, correo_usuario) 
            VALUES (:idUsuario,:nombreUsuario, :Usuario, :passwordUsuario, :correoUsuario);");
            $query->execute([

                ':idUsuario' => $datos['id_usuario'],
                ':nombreUsuario' => $datos['nombre'],
                ':Usuario' => $datos['usuario'],
                ':passwordUsuario' => $datos['password'],
                ':correoUsuario' => $datos['correo'],
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model registro: " . $e->getMessage();
            return false;
        }
    }

    public static function buscarUsuario($idusuario){
        try {
            $con = new Database;
            $query = $con->pdo->prepare("
                SELECT * FROM cat_usuarios WHERE id_usuario = :idUsuario;
            ");
            $query->execute([
                ':idUsuario' => $idusuario
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model revistas: " . $e->getMessage();
            return;
        }
    }

}
