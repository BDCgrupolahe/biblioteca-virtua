<?php require('views/headervertical.view.php'); ?>
<script>
    var IdGlobalUsuario = '<?php echo $_SESSION['id_usuario-' . constant('Sistema')]; ?>';
</script>

<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3>Libreria Virtual</h3>
        </div>
        <div type="hidden" class="container overflow-hidden text-center">
            <div class="row gx-5 justify-content-center mb-3">
                <div class="col-12 col-sm-6 col-md-8 justify-content-center">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar libro por título">
                </div>
                <div class="col-6 col-md-4">
                    <button class="btn btn-outline-info ms-2" id="searchButton" type="button">Buscar</button>
                    <button class="btn btn-outline-danger ms-2" id="cleanButton" type="button">Limpiar</button>
                </div>
            </div>
            <div class="row gx-5">
                <div class="col" id="ocultar">
                    <div class="container">
                        <h2 id="result"></h2>
                        <ul class="cards" id="card-result">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="container-libros" class="container mt-4">
            </div>
            <div class="my-bookstore">
                <main>
                    <div class="content">
                        <h2>Preferidos por Lectores</h2>
                        <p>Estos libros han sido seleccionados por su capacidad única para inspirar, entretener y dejar una huella duradera en quienes se sumergen en sus páginas.</p>
                        <a href="usuarios/ranking/" class="btn">Ir a la colección <i class="fas fa-arrow-right"></i></a>
                    </div>

                    <div class="swiper-container">
                        <div class="swiper">
                            <div class="swiper-wrapper" id="bookSwiperWrapper">
                                <!-- Libros se agregarán aquí dinámicamente -->
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <div class="circle"></div>
                </main>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="libroModal" tabindex="-1" role="dialog" aria-labelledby="libroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"  id="tamaño" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="libroModalLabel">Información del Libro</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
                <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>
            <div class="modal-body" id="libroModalBody">
            </div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<?php require('views/estilos2.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/usuarios.home.js"></script>


