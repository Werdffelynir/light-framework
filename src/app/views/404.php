<?php
/**
 * @type $this \App\Classes\Template
 */

use \App\Classes\Template;


?>
<link rel="stylesheet" href="/css/grid.css">
<div class="table height-100 text-center color-red">
    <div>
        <h1>ERROR 404</h1>
        <h1>Page not found!</h1>
        <p class="color-red"><?=isset($error) ? $error : null?></p>
    </div>
</div>
