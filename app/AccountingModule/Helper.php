<?php
namespace App\AccountingModule;

class Helper{
    static function iam_used_in_sort($sort_by) {
        $__sort_by = isset($_GET['sort_by']) && $_GET['sort_by'] != '' ? $_GET['sort_by'] : NULL;
        $sort_method = isset($_GET['sort_method']) && $_GET['sort_method'] != '' ? $_GET['sort_method'] : 'asc';
        if ($__sort_by && $__sort_by == $sort_by) {
            return '-'.$sort_method;
        }
        return '';
    }
}