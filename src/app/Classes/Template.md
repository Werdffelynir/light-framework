
Class Template.
==============


### Structure
```php
$template = new Template( [ 'path' => '', 'template' => '' ] )

// Exec and return view template
$template->render( $view [, $data [, $callback]] )

// Set session template variable
$template->variable( $name [, $value] )

// Set global template variable
$template->value( $name [, $value] )

// Inject view template $view to layout $position
$template->setPosition($position, $view [, $data [, $callback]] )

// Output layout result
$template->outTemplate( $returned = false )


// Static methods
// Set|Get global template variable
Template::value( $name [, $value] )

// Output view template to layout $position
Template::outPosition($position [, $returned] )
```


/index.php
```php
<?php

try{
    $template = new Template([ 
        'path' => 'views', 
        'template' => 'layout/template'
    ]);

    // Set global variable
    $template->variable('title', 'Page Title');

    // Render view 'main' to 'main' position
    $template->setPosition('main', 'main', []);

    // Render view to 'sidebar' position
    $template->setPosition('sidebar', 'sidebar', []);

    // Output HTML layout
    $template->outTemplate();

}catch (TemplateException $error) {
    
    // TemplateException error viewer
    $error->render();
}
```

/views/layout/template.php
```html
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $this->variable('title') ?></title>
</head>
<body>
    <div id="app">
        <div id="sidebar">
            <?= Template::outPosition('sidebar') ?>
        </div>
        <div id="content">
            <?= Template::outPosition('main') ?>
        </div>
    </div>
</body>
</html>
```

/views/main.php
```html
    <h1>Content</h1>
```

/views/sidebar.php
```html
   <h1>Sidebar</h1>
```

