<?php require('views/headervertical.view.php'); ?>
<script>
    var IdGlobalUsuario = '<?php echo $_SESSION['id_usuario-' . constant('Sistema')]; ?>';
</script>

<body>
    <div id="leaderboards">
        <ul class="toplist">
        </ul>
    </div>
</body>

<?php
require('views/footer.view.php');
?>
<?php
require('views/estilos3.view.php'); 
?>
<script src="<?= constant('URL') ?>public/js/paginas/admin.ranking.js"></script>

