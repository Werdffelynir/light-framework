<?php
/**
 * @type $this \App\Classes\Template
 */

use \App\Classes\Template;


?><div class="table height-100 text-center">
    <div>
        <h1>404</h1>
        <p>Page not found</p>
        <p class="color-red"><?=isset($error) ? $error : null?></p>
    </div>
</div>
