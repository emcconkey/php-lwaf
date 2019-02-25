<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=$this->get("page_title")?></title>


    <link rel="stylesheet" type="text/css" href="/static/html/datatables/datatables.min.css">

    <!-- Bootstrap core CSS -->
    <? if(user::current()->loaded()) { ?>
        <?=user::current()->get_theme_css()?>
    <? } else { ?>
        <link href="/static/html/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <? } ?>

    <link rel="stylesheet" href="/static/html/font-awesome/css/font-awesome.min.css">

    <!-- Custom styles for this template -->
    <link href="/static/html/style.css" rel="stylesheet">

    <script src="/static/html/jquery.min.js"></script>

    <script src="/static/html/bootstrap/js/bootstrap.min.js"></script>

    <script type="text/javascript" charset="utf8" src="/static/html/datatables/datatables.min.js"></script>

    <script src="/static/html/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="/static/html/sweetalert2.min.css">

    <script src="/static/html/featherlight/release/featherlight.min.js"></script>
    <link rel="stylesheet" href="/static/html/featherlight/release/featherlight.min.css">

    <link rel="stylesheet" href="/static/html/pickadate/lib/themes/default.css">
    <link rel="stylesheet" href="/static/html/pickadate/lib/themes/default.date.css">
    <link rel="stylesheet" href="/static/html/pickadate/lib/themes/default.time.css">

    <script src="/static/html/pickadate/lib/picker.js"></script>
    <script src="/static/html/pickadate/lib/picker.date.js"></script>
    <script src="/static/html/pickadate/lib/picker.time.js"></script>
    <script src="/static/html/pickadate/lib/legacy.js"></script>

    <script src="/static/html/script.js"></script>

    <?=$this->get("extra_head")?>

</head>

<body>

<? $this->render("topmenu") ?>
<!-- end header -->

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?=html::get_page_messages()?>
        </div>
    </div>
</div>
