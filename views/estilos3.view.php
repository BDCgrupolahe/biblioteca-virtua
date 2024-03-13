<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css'>
<link rel="stylesheet" href="./style.css">
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<script src="./script.js"></script>

<link href="<?= constant('URL') ?>public/css/ranking.css" rel="stylesheet" />


<script>
    $("#leaderboards .tabTitles span").click(function() {
        $("#leaderboards .active").removeClass("active");
        $("#leaderboards .tabContents ." + $(this).attr("id")).addClass("active");
        $(this).addClass("active");
    });
</script>