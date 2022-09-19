<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$this->title ?? 'Need to setup name'?></title>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.css" />
    <script src="../../js/bootstrap.bundle.js" ></script>
    <script src="../../js/jquery.js"></script>
</head>
<body>
<div>
<?php if (isset($this->params['menubar'])) require 'widgets/menubar.php'; ?>

<main>
    <?=$content ?? ''?>
</main>

</div>
</body>
</html>
