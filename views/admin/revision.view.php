<?php require('views/headervertical.view.php'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h3 class="mx-auto">Autorización de cambios y Revisión de agregados por usuarios</h3>
            <!-- <button id="add-document" class="btn btn-success mx-auto" data-bs-target="#modalNuevoLibro" data-bs-toggle="modal">Agregar <i class="fa-solid fa-circle-plus"></i></button> -->
        </div>
        <div class="card-body">
            <div class="row table-responsive" id="container-libros"></div>
        </div>
    </div>
</div>
<?php require('views/footer.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin.revision.js"></script>