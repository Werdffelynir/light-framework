<?php
/**
 * @type $this \App\Classes\Template
 */

use \App\Classes\Template;

$this->setPosition('sidebar', 'sidebar');

?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="/css/grid.css">
    <link rel="stylesheet" href="/css/main.css">

    <script src="/js/namespaceapplication.js"></script>
    <script src="/js/main.js"></script>

    <title><?=$this->variable('title')?></title>
</head>
<body>

<div id="app" class="table va-child-top">
    <div id="sidebar">
        <?php
        Template::outPosition('sidebar') ?>
    </div>
    <div id="main">
        <?php Template::outPosition('main') ?>
    </div>
</div>

</body>
</html>