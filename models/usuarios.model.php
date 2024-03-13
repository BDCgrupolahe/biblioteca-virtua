<?php
class UsuariosModel extends ModelBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public static function eventos()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT libros.*, cat_genero.genero AS genero_libro, cat_autores.nombre_autor AS autor_libro, agregacion_favoritos.fk_id_libro AS fav_id, agregacion_favoritos.fk_id_usuario AS fav_user FROM libros INNER JOIN cat_genero ON libros.fk_genero_libro = cat_genero.id_genero INNER JOIN cat_autores ON libros.fk_id_autor = cat_autores.id_autor LEFT JOIN (SELECT fk_id_libro, fk_id_usuario FROM agregacion_favoritos WHERE fk_id_usuario = :creadoPor ) AS agregacion_favoritos ON agregacion_favoritos.fk_id_libro = libros.id_libro WHERE libros.estatus_libro = 1 AND libros.estatus_permiso = 1;");
            $query->bindValue(':creadoPor', $_SESSION['id_usuario-' . constant('Sistema')], PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model li: libros" . $e->getMessage();
            return;
        }
    }

    public static function buscarLibro($searchTerm)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT libros.*, 
            cat_genero.genero AS genero_libro, 
            cat_autores.nombre_autor AS autor_libro, 
            agregacion_favoritos.fk_id_libro AS fav_id, 
            agregacion_favoritos.fk_id_usuario AS fav_user 
            FROM libros 
            INNER JOIN cat_genero ON libros.fk_genero_libro = cat_genero.id_genero 
            LEFT JOIN cat_autores ON libros.fk_id_autor = cat_autores.id_autor 
            LEFT JOIN (
                SELECT fk_id_libro, fk_id_usuario
                FROM agregacion_favoritos
                WHERE fk_id_usuario = :creadoPor
            ) AS agregacion_favoritos ON agregacion_favoritos.fk_id_libro = libros.id_libro 
            WHERE 
                titulo_libro LIKE :searchTerm AND libros.estatus_libro = 1 AND libros.estatus_permiso = 1;");
            $query->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
            $query->bindValue(':creadoPor', $_SESSION['id_usuario-' . constant('Sistema')], PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error en el modelo buscarLibro: " . $e->getMessage();
            return array();
        }
    }




    public static function guardarFavorito($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO agregacion_favoritos (fk_id_libro,	fk_id_usuario, feha_agregacion) VALUES (:libroId,:creadoPor,:FechaAgregacion)");
            $query->execute([

                ':libroId' => $datos['libro_id'],
                ':creadoPor' => $_SESSION['id_usuario-' . constant('Sistema')],
                ':FechaAgregacion' => date('Y-m-d H:i:s')
            ]);
            $idtema_resp = $con->pdo->lastInsertId();
            $con->pdo->commit();
            return $idtema_resp;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarFavorito: " . $e->getMessage();
            return false;
        }
    }
    public static function guardarGenero($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_genero (genero) VALUES (:Genero)");
            $query->execute([

                ':Genero' => $datos['genero']
            ]);
            $idtema_resp = $con->pdo->lastInsertId();
            $con->pdo->commit();
            return $idtema_resp;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarGenero: " . $e->getMessage();
            return false;
        }
    }

    public static function actualizarEstado($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("DELETE FROM agregacion_favoritos WHERE fk_id_libro = :libroId AND fk_id_usuario = :creadoPor");
            $query->execute([

                ':libroId' => $datos['libro_id'],
                ':creadoPor' => $_SESSION['id_usuario-' . constant('Sistema')],
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model actualizarEvento: " . $e->getMessage();
            return false;
        }
    }




    public static function obtenerestatus()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM agregacion_favoritos");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model agregacion_favoritos: " . $e->getMessage();
            return;
        }
    }


    public static function librosfavoritos()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT libros.titulo_libro AS titulo_libro, libros.anio_libro AS anio_libro, libros.editorial_libro AS editorial_libro, libros.nombre_archivo AS nombre_archivo, libros.nombre_imagen AS nombre_imagen, cat_autores.nombre_autor AS nombre_autor, COUNT(agregacion_favoritos.fk_id_libro) AS contador_favoritos, ROW_NUMBER() OVER (ORDER BY COUNT(agregacion_favoritos.fk_id_libro) DESC) AS ranking FROM libros INNER JOIN agregacion_favoritos ON libros.id_libro = agregacion_favoritos.fk_id_libro INNER JOIN cat_autores ON cat_autores.id_autor = libros.fk_id_autor WHERE libros.estatus_libro = 1 AND libros.estatus_permiso = 1 GROUP BY libros.id_libro ORDER BY contador_favoritos DESC LIMIT 5;");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model librosfavoritos: " . $e->getMessage();
            return;
        }
    }
    

    public static function loslibrosfavoritos()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT agregacion_favoritos.*, libros.titulo_libro AS titulo_libro, libros.anio_libro AS anio_libro, libros.editorial_libro AS editorial_libro, libros.nombre_archivo AS nombre_archivo, libros.nombre_imagen AS nombre_imagen, cat_autores.nombre_autor AS nombre_autor FROM agregacion_favoritos INNER JOIN libros ON libros.id_libro = agregacion_favoritos.fk_id_libro INNER JOIN cat_autores ON cat_autores.id_autor = libros.fk_id_autor WHERE agregacion_favoritos.fk_id_usuario = :creadoPor");
            $query->bindValue(':creadoPor', $_SESSION['id_usuario-' . constant('Sistema')], PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model loslibrosfavoritos: " . $e->getMessage();
            return;
        }
    }




























    // ###############################
    // #           MOSTRAR           #
    // #           GENEROS            #
    // ###############################

    public static function cat_genero()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_genero");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model cat_genero: " . $e->getMessage();
            return;
        }
    }

    // ###############################
    // #           MOSTRAR           #
    // #           ESTADOS           #
    // ###############################

    public static function cat_autores()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM cat_autores");
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model cat_autores: " . $e->getMessage();
            return;
        }
    }

    // ###############################
    // #      EXISTE EL TOKEN        #
    // ###############################

    public static function existeToken($token)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM libros WHERE token = :token;");
            $query->execute([
                ':token' => $token
            ]);
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model existeToken: " . $e->getMessage();
            return;
        }
    }

    ###############################
    #          GUARDAR            #
    #          MEDICOS            #
    ###############################

    public static function guardarLibro($datos)
{
    try {
        $con = new Database;
        $con->pdo->beginTransaction();


        $query = $con->pdo->prepare("INSERT INTO libros (titulo_libro,fk_id_usuario,editorial_libro,fk_genero_libro,fk_id_autor,anio_libro,nombre_archivo,nombre_imagen,ruta_archivo,token) VALUES (:TituloLibro,:FkIdUsuario,:EditorialLibro,:GeneroLibro,:IdAutor,:AnioLibro,:NombreArchivo,:imagen,:RutaArchivo,:Token)");
        // $query = $con->pdo->prepare("INSERT INTO libros (prefijo, nombre, apellido_p, apellido_m, correo , celular, fk_id_pais, fk_id_estado, ruta_archivo, nombre_archivo, token) VALUES (:Prefijo, :Nombre, :Apellidop, :Apellidom, :Correo, :Celular, :Pais, :Estado, :RutaArchivo,:NombreArchivo, :token)");
        $query->execute([


            'TituloLibro'  => $datos['titulo_libro'],
            'EditorialLibro'  => $datos['editorial_libro'],
            'GeneroLibro'  => $datos['genero'],
            'IdAutor'  => $datos['autor'],
            'AnioLibro'  => $datos['anio_libro'],
            'NombreArchivo'  => $datos['nombre_archivo'] ?? null,
            'imagen'  => $datos['nombre_imagen'] ?? null,
            'RutaArchivo'  => $datos['ruta_archivo'] ?? null,
            'Token'  => $datos['token'],
            ':FkIdUsuario' => $_SESSION['id_usuario-' . constant('Sistema')],
        ]);

        

        $con->pdo->commit();
        return true;
    } catch (PDOException $e) {
        $con->pdo->rollBack();
        echo "Error recopilado model guardarLibro nuevo: " . $e->getMessage();
        return false;
    }
}

    ###############################
    #          ACTUALIZAR         #
    #          MEDICOS            #
    ###############################

    public static function actualizarLibro($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("UPDATE libros SET 
            titulo_libro=:TituloLibro,
            editorial_libro=:EditorialLibro,
            anio_libro=:AnioLibro,
            fk_genero_libro=:GeneroLibro,
            fk_id_autor=:IdAutor,
            nombre_archivo=:NombreArchivo,
            ruta_archivo=:RutaArchivo,
            nombre_imagen=:imagen,
            estatus_permiso = 0
            WHERE id_libro = :id");

            $query->execute([
            'id'  => $datos['id_libro'],
            'TituloLibro'  => $datos['titulo_libro'],
            'EditorialLibro'  => $datos['editorial_libro'],
            'AnioLibro'  => $datos['anio_libro'],
            'GeneroLibro'  => $datos['genero'],
            'IdAutor'  => $datos['autor'],
            'NombreArchivo'  => $datos['nombre_archivo'] ?? null,
            'imagen'  => $datos['nombre_imagen'] ?? null,
            'RutaArchivo'  => $datos['ruta_archivo'] ?? null,
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model actualizarLibro: " . $e->getMessage();
            return false;
        }
    }

    ###############################
    #       MOSTRAR JSON          #
    #          MEDICOS            #
    ###############################

    public static function libroview()
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT libros.id_libro, 
            libros.titulo_libro, 
            libros.editorial_libro, 
            libros.fk_genero_libro, 
            libros.fk_id_autor, 
            libros.anio_libro, 
            libros.token, 
            libros.estatus_permiso,
            libros.estatus_libro, 
            cat_autores.id_autor, 
            libros.nombre_archivo AS archivo_old, 
            cat_autores.nombre_autor AS autor_libro, 
            cat_genero.id_genero, cat_genero.genero AS 
            genero_libro FROM libros 
            INNER JOIN cat_autores ON libros.fk_id_autor = cat_autores.id_autor 
            INNER JOIN cat_genero ON libros.fk_genero_libro = cat_genero.id_genero 
            WHERE fk_id_usuario = :creadoPor;");
            $query->bindValue(':creadoPor', $_SESSION['id_usuario-' . constant('Sistema')], PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $e) {
            echo "Error recopilado model medico: " . $e->getMessage();
            return;
        }
    }



    // ###############################
    // #          ELIMINAR           #
    // #           MEDICO            #
    // ###############################

    public static function eliminarLibro($id)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
    
            $query = $con->pdo->prepare("SELECT estatus_libro FROM libros WHERE id_libro = :id;");
            $query->execute([
                ':id' => base64_decode(base64_decode($id))
            ]);
            $condicionActual = $query->fetch(PDO::FETCH_ASSOC)['estatus_libro'];
    
            $nuevoValorCondicion = ($condicionActual == 1) ? 0 : 1;

            $query = $con->pdo->prepare("UPDATE libros SET estatus_libro = :condicion WHERE id_libro = :id;");
            $query->execute([
                ':id' => base64_decode(base64_decode($id)),
                ':condicion' => $nuevoValorCondicion
            ]);
    
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model eliminarMedico: " . $e->getMessage();
            return false;
        }
    }
    

    // ###############################
    // #           BUSCAR            #
    // #           MEDICO            #
    // ###############################

    public static function buscarusuariosLibro($id_libro)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM libros WHERE id_libro = :id_libro");
            // $query = $con->pdo->prepare("SELECT *, nombre_archivo AS nombre_archivo_old FROM medicos WHERE id = :id");
            // $query = $con->pdo->prepare("SELECT * FROM medicos WHERE id = :id");
            $query->execute([
                ':id_libro' => base64_decode(base64_decode($id_libro))
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarusuariosLibro: " . $e->getMessage();
            return;
        }
    }



    // ###############################
    // #           BUSCAR            #
    // # DOCUMENTO POR TOKEN Y RUTA  #
    // ###############################
    public static function buscarDocumento($id,$ruta_archivo,$token)
    {
        try {
            $con = new Database;
            $query = $con->pdo->prepare("SELECT * FROM medicos WHERE id = :id AND ruta_archivo = :ruta_archivo AND token = :token");
            
            $query->execute([
                ':ruta_archivo' => ($ruta_archivo),
                ':token' =>($token),
                ':id' =>($id)
            ]);
            return $query->fetch();
        } catch (PDOException $e) {
            echo "Error recopilado model buscarMedicos: " . $e->getMessage();
            return;
        }
    }



    public static function guardarusuariosGenero($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_genero (genero) VALUES (:Genero)");
            $query->execute([

                ':Genero' => $datos['genero']
            ]);
            $idtema_resp = $con->pdo->lastInsertId();
            $con->pdo->commit();
            return $idtema_resp;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarusuariosGenero: " . $e->getMessage();
            return false;
        }
    }
    
    public static function guardarAutor($datos)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("INSERT INTO cat_autores (nombre_autor) VALUES (:Autor)");
            $query->execute([

                ':Autor' => $datos['autor']
            ]);
            $idtema_resp = $con->pdo->lastInsertId();
            $con->pdo->commit();
            return $idtema_resp;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model guardarGenero: " . $e->getMessage();
            return false;
        }
    }




    public static function eliminarfavoritoLibro($id)
    {
        try {
            $con = new Database;
            $con->pdo->beginTransaction();
            $query = $con->pdo->prepare("DELETE FROM agregacion_favoritos WHERE id_agregacion = :id;");
            // echo $query->queryString;  // Imprime la consulta SQL para depuración
            $query->execute([
                ':id' => base64_decode(base64_decode($id))
            ]);
            $con->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $con->pdo->rollBack();
            echo "Error recopilado model eliminarfavoritoLibro: " . $e->getMessage();
            return false;
        }
    }
    
    
}
