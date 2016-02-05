<!DOCTYPE html>
<html lang="sk">
<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="keywords" content="">

    <meta name="author" content="Matúš Petrofčík">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="google" content="notranslate">

    <!-- title -->
    <title><?= (isset($title) ? $title : "todo") ?></title>

    <!-- style -->
    <link rel="stylesheet" type="text/css" href="<?= url() ?>/assets/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="<?= url() ?>/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= url() ?>/assets/css/app.css">

    <!-- script -->
    <script type="text/javascript" src="<?= url() ?>/assets/js/jquery.js"></script>
    <script type="text/javascript" src="<?= url() ?>/assets/js/bootstrap.js"></script>
    <script>
        var base_url = '<?= url() ?>';
    </script>
    <script type="text/javascript" src="<?= url() ?>/assets/js/app.js"></script>
</head>
<body class="<?= (isset($class) ? $class : "default") ?>">

<!--START .container-->
<div class="container">

    <header>
        <?php if (has_messages()): foreach (get_messages() as $message): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Message:</strong> <?= $message ?>
                    </div>
                </div>
            </div>
        <?php endforeach; endif ?>

        <div class="row">
            <div class="col-md-12 text-center">
                <h1><?= (isset($title) ? $title : "Welcome") ?></h1>
            </div>
        </div>
    </header>
