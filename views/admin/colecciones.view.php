<?php require('views/headervertical.view.php'); ?>
<div class="container">
  <div class="card">
    <div class="card-header d-flex justify-content-center flex-wrap">
      <h3>Libros | Favoritos</h3>
    </div>

    <body>
      <div class="component">
        <ul class="align" id="container-eventos">
        </ul>
      </div>
      <script src='//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    </body>
  </div>
</div>
<?php require('views/footer.view.php'); ?>
<?php require('views/estilos1.view.php'); ?>
<script src="<?= constant('URL') ?>public/js/paginas/admin.favoritos.js"></script>