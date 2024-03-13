<?php
class Admin extends ControllerBase
{
    function __construct()
    {
        parent::__construct();
    }

    // ###############################
    // #     RENDER VISTA INDEX      #
    // ###############################

    function render()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/index");
        } else {
            $this->recargar();
        }
    }
    function cardsLibros()
    {
        try {
            $cardsLibros = AdminModel::cardsLibros();
            echo json_encode($cardsLibros);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador cardsLibros: " . $th->getMessage();
            return;
        }
    }

    function buscarLibro($param = null)
    {
        try {
            // Verifica si se proporciona un término de búsqueda
            $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';
            
            // Llama al método del modelo para buscar libros
            $libros = AdminModel::buscarLibro($searchTerm);

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
                $resp = AdminModel::guardarFavorito($_POST);
            } else {
                $resp = AdminModel::actualizarEstado($_POST);
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
                $resp = AdminModel::guardarGenero($_POST);
            } else {
                $resp = AdminModel::guardarAutor($_POST);
            }
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Creado correctamente' : 'actualizado correc',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creo correctamente.' : 'Se actualizo correctamente el Favorito'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'no pudo creo correctamente' : 'no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente.' : 'No se pudo actualizar correctamente el evento'
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
    function guardarAutores()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $resp = AdminModel::guardarAutores($_POST);
            } else {
                $resp = AdminModel::actualizarEstado($_POST);
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
            $estatus = AdminModel::obtenerestatus();
            echo json_encode($estatus);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador cat_profesores: " . $th->getMessage();
            return;
        }
    }



    function librosfavoritos()
    {
        try {
            $librosfavoritos = AdminModel::librosfavoritos();
            echo json_encode($librosfavoritos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador librosfavoritos: " . $th->getMessage();
            return;
        }
    }


    function colecciones($param = null)
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/colecciones");
        } else {
            $this->recargar();
        }
    }



    function loslibrosfavoritos()
    {
        try {
            $loslibrosfavoritos = AdminModel::loslibrosfavoritos();
            echo json_encode($loslibrosfavoritos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador loslibrosfavoritos: " . $th->getMessage();
            return;
        }
    }

    // ###############################
    // #  TOP 50 DE LIBROS FAVITOS   #
    // #           LIBROS            #
    // ###############################

    function librosfavoritostop()
    {
        try {
            $librosfavoritos = AdminModel::librosfavoritostop();
            echo json_encode($librosfavoritos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador librosfavoritostop: " . $th->getMessage();
            return;
        }
    }

    // ###############################
    // #           RENDER            #
    // #           LIBROS            #
    // ###############################


    function libros()
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/libros");
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
            $prefijos = AdminModel::cat_genero();
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
            $prefijos = AdminModel::cat_autores();
            echo json_encode($prefijos);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador cat_autores: " . $th->getMessage();
            return;
        }
    }



    // ###############################
    // #           MOSTRAR           #
    // #           LIBRO            #
    // ###############################


    function libroview($param = null)
    {
        try {
            $libro = AdminModel::libroview();
            echo json_encode($libro);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador  libroview: " . $th->getMessage();
            return;
        }
    }
    function librorevision($param = null)
    {
        try {
            $libro = AdminModel::librorevision();
            echo json_encode($libro);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador librorevision: " . $th->getMessage();
            return;
        }
    }

    // ###############################
    // #           GUARDAR           #
    // #           LIBRO            #
    // ###############################

    function guardarLibro()
    {
    try {
        
        $id_libro = $_POST['id_libro'];
        $archivoold = $_POST['nombreArchivo'];
        $imagenold = $_POST['nombreImagen'];
        
        $token = '';
        
        // http://localhost/programa.academico-main/pdfjs-4.0.379-dist/web/viewer.html?file=b7c1a298_55.pdf
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
                while (AdminModel::existeToken($token)) {
                    $token = substr(bin2hex(random_bytes(4)), 0, 8);
                    $nuevo_nombre_archivo = $token . '_' . $id_libro . '.' . $extension;
                    $ruta_archivo = $ruta . $nuevo_nombre_archivo;
                }

                $_POST['token'] = $token;

                $resp = AdminModel::guardarLibro($_POST);
            } else {
                $resp = AdminModel::actualizarLibro($_POST);
            }

            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Libro creado' : 'Libro actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'Se creó correctamente el Libro.' : 'Se actualizó correctamente el Libro'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => ($_POST['tipo'] == 'nuevo') ? 'Libro no creado' : 'Libro no actualizado',
                    'respuesta' => ($_POST['tipo'] == 'nuevo') ? 'No se pudo crear correctamente el Libro.' : 'No se pudo actualizar correctamente el programa'
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
    // #           LIBRO            #
    // ###############################

    function eliminarLibro($param = null)
    {
        try {
            $resp = AdminModel::eliminarLibro($param[0]);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'listo',
                    'respuesta' => 'Se actualizo el libro correctamente.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'libro no eliminada',
                    'respuesta' => 'No se pudo eliminar correctamente el libro.'
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

    function revisionlibro($param = null)
    {
        try {
            $resp = AdminModel::revisionlibro($param[0]);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'listo',
                    'respuesta' => 'Se aprovo el libro correctamente.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'libro no eliminada',
                    'respuesta' => 'No se pudo eliminar correctamente el libro.'
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
    // #           LIBRO            #
    // ###############################

    function buscaradminLibro($param = null)
    {
        try {
            $libro = AdminModel::buscaradminLibro($param[0]);
            echo json_encode($libro);
        } catch (\Throwable $th) {
            echo "Error recopilado controlador buscaradminLibro: " . $th->getMessage();
            return;
        }
    }



    function guardaradminGenero()
    {
        try {
            if ($_POST['tipo'] == 'nuevo') {
                $resp = AdminModel::guardaradminGenero($_POST);
            } else {
                $resp = AdminModel::guardarAutor($_POST);
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




    function revision($param = null)
    {
        if ($this->verificarAdmin()) {
            $this->view->render("admin/revision");
        } else {
            $this->recargar();
        }
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

    function eliminarlibrodesaprovado($param = null)
    {
        try {
            $resp = AdminModel::eliminarlibrodesaprovado($param[0]);
            if ($resp != false) {
                $data = [
                    'estatus' => 'success',
                    'titulo' => 'listo',
                    'respuesta' => 'Se Elimino el libro correctamente.'
                ];
            } else {
                $data = [
                    'estatus' => 'warning',
                    'titulo' => 'libro no eliminado',
                    'respuesta' => 'No se pudo eliminar correctamente el libro.'
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
        if ($this->verificarAdmin()) {
            $this->view->render("admin/ranking");
        } else {
            $this->recargar();
        }
    }
}