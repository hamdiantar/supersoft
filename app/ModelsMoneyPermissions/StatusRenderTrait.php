<?php
namespace App\ModelsMoneyPermissions;

trait StatusRenderTrait {
    protected $status;
    protected $translated_status;

    function render_status($status ,$translated_status) {
        $this->status = $status;
        $this->translated_status = $translated_status;
        $function_name = 'render_'.$this->status;
        return $this->$function_name();
    }

    private function render_pending() {
        return "<span class='label label-warning'>$this->translated_status</span>";
    }

    private function render_approved() {
        return "<span class='label label-success'>$this->translated_status</span>";
    }

    private function render_rejected() {
        return "<span class='label label-danger'>$this->translated_status</span>";
    }
}