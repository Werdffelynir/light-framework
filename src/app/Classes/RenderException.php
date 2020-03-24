<?php


namespace App\Classes;


trait RenderException
{
    public function render()
    {
        $message = $this->message;
        $code = $this->code;
        $file = $this->file;
        $line = $this->line;
        $trace = print_r($this->getTrace(), true);

        die ("
<style>
.ce_error_wrapper{background-color: darkred; color: #e0e0e0}
.ce_header, .ce_body{padding: 1rem; color: #e0e0e0}
.ce_header{background-color: #640000;}
.ce_body strong {padding-left: 0.5rem; width: 100px; display: inline-block;}
.ce_trace{padding: 1rem; background-color: #252927; color: #e0e0e0;}
</style>
<div class='ce_error_wrapper'>
<div class='ce_header'>
    <h1>CoreException Trace</h1>
    <h3>Message: <code>$message</code></h3>
</div>
<div class='ce_body'>
    <div><strong>Code:</strong> <code>$code</code></div>  
    <div><strong>File:</strong> <code>$file : $line</code></div>  
    <div><strong>Trace:</strong> </div>  
</div>
<div class='ce_trace'>
    <pre>$trace</pre>
</div>
</div>   ");
    }
}