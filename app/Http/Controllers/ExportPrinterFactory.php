<?php
namespace App\Http\Controllers;

class ExportPrinterFactory {
    protected $invoker;
    protected $action_for;

    function __construct($invoker ,$action_for) {
        $this->invoker = $invoker;
        $this->action_for = $action_for;
    }

    function __invoke() {
        $invoker = $this->invoker;
        return $invoker($this->action_for);
    }
}