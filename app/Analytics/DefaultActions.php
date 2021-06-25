<?php

namespace App\Analytics;

class DefaultActions {
    private static function word_tokenizer($string) {
        $tokens = explode(" " ,$string);
        $tokens[count($tokens) - 1] = "<br><span class='bold-span'>".$tokens[count($tokens) - 1]."</span>";
        return implode(" " ,$tokens);
    }

    static function get() {
        return [
            [
                'title' => __('analytics.default-defenitions'),
                'div-background' => 'bg-primary custom-bg-color-definition',
                'fa_icon' => 'fa-users',
                'actions' => [
                    [
                        'title' => self::word_tokenizer(__('analytics.add-part')),
                        'status' => self::checkAutorization('create' ,'parts'),
                        'link' => route("admin:parts.create")
                    ],
                    [
                        'title' => self::word_tokenizer(__('analytics.add-customer')),
                        'status' => self::checkAutorization('create' ,'customers'),
                        'link' => route("admin:customers.create")
                    ],
                    [
                        'title' => self::word_tokenizer(__('analytics.add-supplier')),
                        'status' => self::checkAutorization('create' ,'suppliers'),
                        'link' => route("admin:suppliers.create")
                    ]
                ]
            ],
            [
                'title' => __('analytics.invoices'),
                'div-background' => 'bg-success custom-bg-color-invoices',
                'fa_icon' => 'fa-files-o',
                'actions' => [
                    [
                        'title' => self::word_tokenizer(__('analytics.add-buy-invoice')),
                        'status' => self::checkAutorization('create' ,'purchase_invoices'),
                        'link' => route("admin:purchase-invoices.create")
                    ],
                    [
                        'title' => self::word_tokenizer(__('analytics.add-buy-invoice-return')),
                        'status' => self::checkAutorization('create' ,'purchase_return_invoices'),
                        'link' => route("admin:purchase_returns.create")
                    ],
                    [
                        'title' => self::word_tokenizer(__('analytics.add-sale-invoice')),
                        'status' => self::checkAutorization('create' ,'sales_invoices'),
                        'link' => route("admin:sales.invoices.create")
                    ],
                    [
                        'title' => self::word_tokenizer(__('analytics.add-sale-invoice-return')),
                        'status' => self::checkAutorization('create' ,'sales_invoices_return'),
                        'link' => route("admin:sales.invoices.return.create")
                    ]
                ]
            ],
            [
                'title' => __('analytics.maintainance-services'),
                'div-background' => 'bg-info custom-bg-color-services',
                'fa_icon' => 'fa-cogs',
                'actions' => [
                    [
                        'title' => self::word_tokenizer(__('analytics.add-service')),
                        'status' => self::checkAutorization('create' ,'work_card'),
                        'link' => route("admin:work-cards.create")
                    ],
                    [
                        'title' => self::word_tokenizer(__('analytics.show-services')),
                        'status' => self::checkAutorization('view' ,'work_card'),
                        'link' => route("admin:work-cards.index")
                    ]
                ]
            ],
            [
                'title' => __('analytics.default-operations'),
                'div-background' => 'bg-warning custom-bg-color-operations',
                'fa_icon' => 'fa-file',
                'actions' => [
                    [
                        'title' => self::word_tokenizer(__('analytics.add-price-offers')),
                        'status' => self::checkAutorization('create' ,'quotations'),
                        'link' => route("admin:quotations.create")
                    ],
                    [
                        'title' => self::word_tokenizer(__('analytics.add-fast-sale')),
                        'status' => self::checkAutorization('create' ,'fast sale invoice'),
                        'link' => '#'//route("admin:")
                    ],
                    [
                        'title' => self::word_tokenizer(__('analytics.add-fast-maintainance')),
                        'status' => self::checkAutorization('create' ,'fast maintainance'),
                        'link' => '#'//route("admin:")
                    ]
                ]
            ]
        ];
    }

    private static function checkAutorization($action ,$module) {
        return auth()->user()->can($action .'_'. $module);
    }
}