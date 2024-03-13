<?php require('views/headervertical.view.php'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3 class="mx-auto">Crear Libros</h3>
            <p>Las filas resaltadas en color <mark class="text-danger">rojo</mark>  indican que se requiere autorización para agregar o realizar cambios en un libro. Por favor, dirígete a la pestaña de revisión para tomar las acciones correspondientes.</p>
            <button id="add-document" class="btn btn-success mx-auto" data-bs-target="#modalNuevoLibro" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button>
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-libros"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin.libros.js"></script>
<div class="modal fade" id="modalNuevoLibro" aria-hidden="true" aria-labelledby="modalNuevoLibroLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-libros" action="javascript:;" class="needs-validation" novalidate method="post" enctype="multipart/form-data">
            <input type="hidden" name="tipo" id="tipo" value="nuevo" readonly>
            <input type="hidden" name="id_libro" id="id_libro" readonly>
            <input type="hidden" name="token" id="token" readonly>
            <input type="hidden" name="ruta" id="ruta" readonly>
            <input type="hidden" name="oldarchivo" id="oldarchivo" readonly>
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalNuevoLibroLabel">Agregar Libro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="">Titulo del libro<small class="text-danger">*</small></label></label>
                            <input type="text" class="form-control" name="titulo_libro" id="titulo_libro" placeholder="Titulo del libro..." required>
                            <div class="invalid-feedback">
                                Ingrese el Titulo del libro.
                            </div>
                        </div>


                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="">Editorial del libro<small class="text-danger">*</small></label></label>
                            <input type="text" class="form-control" name="editorial_libro" id="editorial_libro" placeholder="Editorial del libro..." required>
                            <div class="invalid-feedback">
                                Ingrese su Editorial del libro.
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="">Genero <small class="text-danger">*</small></label>
                            <select class="form-control" name="genero" id="genero" required>
                                <option value="">Seleccionar genero...</option>
                            </select>
                            <div class="invalid-feedback">
                                Seleccione una opción, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="">Autor <small class="text-danger">*</small></label>
                            <select class="form-control" name="autor" id="autor" required>
                                <option value="">Seleccionar autor...</option>
                            </select>
                            <div class="invalid-feedback">
                                Seleccione una opción, por favor.
                            </div>
                        </div>
                        <div id="contenedor-agregar" class="col-sm-12 col-md-12 col-lg-12 col-xl-12 d-none">
                            <label for="">Salón</label>
                            <input type="text" class="form-control" name="nuevo_salon" id="nuevo_salon" disabled="true">
                            <div class="invalid-feedback">
                                Ingrese un nombre de salón, por favor.
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="">Año de publicación</label></label>
                            <input type="text" class="form-control" name="anio_libro" id="anio_libro" placeholder="Año de publicación...">
                            <div class="invalid-feedback">
                                Ingrese el Año del libro.
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="">Selecciona un archivo</label>
                            <input type="file" class="form-control-file" name="archivo" id="archivo" accept="application/pdf">
                            <input type="hidden" name="nombreArchivo" id="nombreArchivo">
                            <div class="invalid-feedback">
                                Seleccione un archivo, por favor.
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                            <label for="">Selecciona una imagen</label>
                            <input type="file" class="form-control-file" name="imagen" id="imagen" accept="image/*">
                            <input type="hidden" name="nombreImagen" id="nombreImagen">
                            <div class="invalid-feedback">
                                Seleccione un imagen, por favor.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger btn-cancelar-libros" data-bs-dismiss="modal">Cancelar</button>
                    <button data-formulario="form-libros" data-tipo="nuevo" type="button" class="btn btn-success btn-save-libros">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class="modal fade" id="modalNuevoGenero" aria-hidden="true" aria-labelledby="modalNuevoGeneroLabel" tabindex="-1">
    <div class="modal-dialog modal-right">
        <form id="form-genero-nuevo" action="javascript:;" class="needs-validation" novalidate method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalNuevoGeneroLabel">Agregar nuevo Genero</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-12">
                            <label for="">Genero <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="genero" id="genero" required>
                            <input type="hidden" class="form-control" name="tipo" id="tipo" value="nuevo">
                            <div class="invalid-feedback">
                                Ingrese un genero, por favor.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button data-formulario="form-genero-nuevo" data-tipo="nuevo" type="button" class="btn btn-success btn-save-generos">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modalNuevoAutor" aria-hidden="true" aria-labelledby="modalNuevoAutorLabel" tabindex="-1">
    <div class="modal-dialog modal-right">
        <form id="form-autor-nuevo" action="javascript:;" class="needs-validation" novalidate method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalNuevoAutorLabel">Agregar nuevo Autor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6 col-xl-12">
                            <label for="">Autor <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="autor" id="autor" required>
                            <input type="hidden" class="form-control" name="tipo" id="tipo" value="actualizar">
                            <div class="invalid-feedback">
                                Ingrese un autor, por favor.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button data-formulario="form-autor-nuevo" data-tipo="nuevo" type="button" class="btn btn-success btn-save-generos">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>