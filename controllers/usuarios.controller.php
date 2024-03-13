<?php
// require_once("public/vendor/phpmailer/src/PHPMailer.php");
// require_once("public/vendor/phpmailer/src/Exception.php");
// require_once("public/vendor/phpmailer/src/SMTP.php");
// require_once("public/phpqrcode/qrlib.php");


class Usuarios extends ControllerBase
{
    function __construct()
    {
        parent::__construct();
    }
    /* Vistas */
    function render()
    {
        if ($this->verificarUsuarios()) {
            $this->view->render("usuarios/index");
        } else {
            $this->recargar();
        }
    }
    function eventos()
    {
        try {
            $eventos = UsuariosModel::eventos();
            echo json_encode($eventos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador eventos: " . $th->getMessage();
            return;
        }
    }

    function buscarLibro($param = null)
    {
        try {
            // Verifica si se proporciona un término de búsqueda
            $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

            // Llama al método del modelo para buscar libros
            $libros = UsuariosModel::buscarLibro($searchTerm);

            // Devuelve la respuesta en formato JSON
            echo json_encode($libros);
        } catch (\Throwable $th) {
            echo "Error en el controlador buscarLibro: " . $th->getMessage();
            return;
        }
    }

    function guardarFavorito()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $resp = UsuariosModel::guardarFavorito($_POST);
            } else {
                $resp = UsuariosModel::actualizarEstado($_POST);
            }
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Favorito creado' : 'Favorito actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creo correctamente el Favorito.' : 'Se actualizo correctamente el Favorito'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Favorito no creado' : 'Favorito no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente el Favorito.' : 'No se pudo actualizar correctamente el evento'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas.Error:' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }
    function guardarGenero()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $resp = UsuariosModel::guardarGenero($_POST);
            } else {
                $resp = UsuariosModel::actualizarEstado($_POST);
            }
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Favorito creado' : 'Favorito actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creo correctamente el Favorito.' : 'Se actualizo correctamente el Favorito'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Favorito no creado' : 'Favorito no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente el Favorito.' : 'No se pudo actualizar correctamente el evento'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas.Error:' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }



    function obtenerestatus()
    {
        try {
            $estatus = UsuariosModel::obtenerestatus();
            echo json_encode($estatus);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador cat_profesores: " . $th->getMessage();
            return;
        }
    }



    function librosfavoritos()
    {
        try {
            $librosfavoritos = UsuariosModel::librosfavoritos();
            echo json_encode($librosfavoritos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador librosfavoritos: " . $th->getMessage();
            return;
        }
    }


    function colecciones($param = null)
    {
        if ($this->verificarUsuarios()) {
            $this->view->render("usuarios/colecciones");
        } else {
            $this->recargar();
        }
    }


    function loslibrosfavoritos()
    {
        try {
            $loslibrosfavoritos = UsuariosModel::loslibrosfavoritos();
            echo json_encode($loslibrosfavoritos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador loslibrosfavoritos: " . $th->getMessage();
            return;
        }
    }




    function libros()
    {
        if ($this->verificarUsuarios()) {
            $this->view->render("usuarios/libros");
        } else {
            $this->recargar();
        }
    }


    // ###############################
    // #           MOSTRAR           #
    // #           GENEROS           #
    // ###############################

    function cat_genero()
    {
        try {
            $prefijos = UsuariosModel::cat_genero();
            echo json_encode($prefijos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador cat_genero: " . $th->getMessage();
            return;
        }
    }

    // ###############################
    // #           MOSTRAR           #
    // #           ESTADOS           #
    // ###############################


    function cat_autores()
    {
        try {
            $prefijos = UsuariosModel::cat_autores();
            echo json_encode($prefijos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador cat_autores: " . $th->getMessage();
            return;
        }
    }



    // ###############################
    // #           MOSTRAR           #
    // #           MEDICO            #
    // ###############################


    function libroview($param = null)
    {
        try {
            $libro = UsuariosModel::libroview();
            echo json_encode($libro);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador medico: " . $th->getMessage();
            return;
        }
    }

    // ###############################
    // #           GUARDAR           #
    // #           MEDICO            #
    // ###############################

    function guardarLibro()
    {
        try {

            $id_libro = $_POST['id_libro'];
            $archivoold = $_POST['nombreArchivo'];
            $imagenold = $_POST['nombreImagen'];
            $token = '';


            if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
                $ruta = './pdfjs-4.0.379-dist/web/';
                $extension = 'pdf';


                if ($_POST['tipo'] == 'nuevo') {
                    $token = substr(bin2hex(random_bytes(4)), 0, 8);
                    $nuevo_nombre_archivo = $token . '_' . $id_libro . '.' . $extension;
                    $ruta_archivo = $ruta . $nuevo_nombre_archivo;

                    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo)) {
                        $_POST['ruta_archivo'] = $ruta_archivo;
                        $_POST['nombre_archivo'] = $nuevo_nombre_archivo;
                    } else {
                        throw new Exception('Error al mover el archivo.');
                    }
                } else {
                    $token = $_POST['token'];
                    // $nombre = $_POST['titulo_libro'];
                    $extension = 'pdf';
                    $nuevo_nombre_archivo = $token . '_' . $id_libro . '.' . $extension;
                    $ruta_archivo = $ruta . $nuevo_nombre_archivo;

                    $_POST['nombre_archivo'] = 'nombre_archivo';

                    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo)) {
                        $_POST['ruta_archivo'] = $ruta_archivo;
                        $_POST['nombre_archivo'] = $nuevo_nombre_archivo;
                    } else {
                        throw new Exception('Error al mover el archivo.');
                    }
                }
            } else {
                $token = $_POST['token'];
                // $nombre = $_POST['titulo_libro'];
                $id_libro = $_POST['id_libro'];
                $archivoold = $_POST['nombreArchivo'];
                $extension = 'pdf';
                $ruta = './pdfjs-4.0.379-dist/web/';
                $nuevo_nombre_archivo = $token . '_' . $id_libro . '.' . $extension;
                $ruta_archivo = $ruta . $nuevo_nombre_archivo;
                $ruta_archivo_old = $ruta . $archivoold;
                try {
                    if (file_exists($ruta_archivo_old)) {
                        if (rename($ruta_archivo_old, $ruta_archivo)) {
                            $_POST['ruta_archivo'] = $ruta_archivo;
                            $_POST['nombre_archivo'] = $nuevo_nombre_archivo;
                        }
                    } else {
                        throw new Exception('Error al cambiar el nombre al archivo.');
                    }
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            }




            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
                $ruta_imagen = './imagenes/';
                $id_libro = $_POST['id_libro'];
                $extension_imagen = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);


                $nuevo_nombre_imagen = $token . '_' . $id_libro . '.' . $extension_imagen;
                $ruta_imagen_completa = $ruta_imagen . $nuevo_nombre_imagen;
                $_POST['nombre_imagen'] = $nuevo_nombre_imagen;


                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen_completa)) {
                    $_POST['ruta_imagen'] = $ruta_imagen_completa;
                    $_POST['nombre_imagen'] = $nuevo_nombre_imagen;
                } else {
                    throw new Exception('Error al mover la imagen.');
                }
            }else {
                // No hay un nuevo archivo de imagen, mantén el nombre anterior
                $_POST['nombre_imagen'] = $imagenold;
                // $_POST['ruta_imagen'] = './imagenes/' . $archivoold;
            }

            if ($_POST['tipo'] == 'nuevo') {
                while (UsuariosModel::existeToken($token)) {
                    $token = substr(bin2hex(random_bytes(4)), 0, 8);
                    $nuevo_nombre_archivo = $token . '_' . $id_libro . '.' . $extension;
                    $ruta_archivo = $ruta . $nuevo_nombre_archivo;
                }

                $_POST['token'] = $token;

                $resp = UsuariosModel::guardarLibro($_POST);
            } else {
                $resp = UsuariosModel::actualizarLibro($_POST);
            }

            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Libro creado' : 'Libro actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creó correctamente el Libro.' : 'Se actualizó correctamente el Libro',
                    'verificacion' => ($_POST['tipo'] == 'nuevo') ? 'Debe ser revisado por el administrador. Una vez revisado y aprobado, el libro será dado de alta y podrás utilizarlo.' : 'Se actualizó correctamente el Libro - Debe ser revisado por el administrador. Una vez revisado y aprobado, el libro será dado de alta y podrás utilizarlo.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Libro no creado' : 'Libro no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente el Libro.' : 'No se pudo actualizar correctamente el programa',
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error en el servidor',
                'respuesta' => 'Contacte al área de sistemas. Error: ' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }







    // ###############################
    // #         ELIMINAR            #
    // #           MEDICO            #
    // ###############################

    function eliminarLibro($param = null)
    {
        try {
            $resp = UsuariosModel::eliminarLibro($param[0]);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'listo',
                    'respuesta' => 'Se actualizo el estado correctamente.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'medico no eliminada',
                    'respuesta' => 'No se pudo eliminar correctamente la medico.'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas.Error:' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }


    // ###############################
    // #           BUSCAR            #
    // #           MEDICO            #
    // ###############################

    function buscarusuariosLibro($param = null)
    {
        try {
            $libro = UsuariosModel::buscarusuariosLibro($param[0]);
            echo json_encode($libro);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscarMedico: " . $th->getMessage();
            return;
        }
    }



    function guardarusuariosGenero()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $resp = UsuariosModel::guardarusuariosGenero($_POST);
            } else {
                $resp = UsuariosModel::guardarAutor($_POST);
            }
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Genero creado' : 'Autor creado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creo correctamente el Genero.' : 'Se creo correctamente el Autor'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Genero no creado' : 'Autor no creado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente el Genero.' : 'No se pudo crear correctamente el autor'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas.Error:' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }



    function eliminarfavoritoLibro($id)
    {
        try {
            $resp = UsuariosModel::eliminarfavoritoLibro($id[0]);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'listo',
                    'respuesta' => 'Se Elimino el libro de tus favoritos correctamente.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'libro no eliminado de favoritos',
                    'respuesta' => 'No se pudo eliminar de los favoritos.'
                ];
            }
        } catch (\Throwable $th) {
            $data = [
                'estatus' => 'error',
                'titulo' => 'Error servidor',
                'respuesta' => 'Contacte al área de sistemas.Error:' . $th->getMessage()
            ];
            return;
        }
        echo json_encode($data);
    }




    function ranking($param = null)
    {
        if ($this->verificarUsuarios()) {
            $this->view->render("usuarios/ranking");
        } else {
            $this->recargar();
        }
    }
}
