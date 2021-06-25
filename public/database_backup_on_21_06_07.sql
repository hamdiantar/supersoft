

CREATE TABLE `account_relations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `accounts_tree_root_id` bigint(20) NOT NULL,
  `accounts_tree_id` bigint(20) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `related_model_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'we use this column to identify the data to complete the account relations where to found',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `account_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `account_from_id` bigint(20) unsigned NOT NULL,
  `account_to_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_transfers_account_from_id_foreign` (`account_from_id`),
  KEY `account_transfers_account_to_id_foreign` (`account_to_id`),
  KEY `account_transfers_branch_id_foreign` (`branch_id`),
  KEY `account_transfers_created_by_foreign` (`created_by`),
  CONSTRAINT `account_transfers_account_from_id_foreign` FOREIGN KEY (`account_from_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `account_transfers_account_to_id_foreign` FOREIGN KEY (`account_to_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `account_transfers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `account_transfers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `special` tinyint(1) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accounts_branch_id_foreign` (`branch_id`),
  CONSTRAINT `accounts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `accounts_trees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `accounts_tree_id` bigint(20) DEFAULT NULL,
  `tree_level` int(11) NOT NULL DEFAULT 1,
  `account_type_name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type_name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_nature` enum('debit','credit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'debit',
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `custom_type` int(11) NOT NULL DEFAULT 1 COMMENT '1 : Budget ,2 : Income List, 3 : Trading Account',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `accounts_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `account_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `accounts_users_user_id_foreign` (`user_id`),
  KEY `accounts_users_account_id_foreign` (`account_id`),
  CONSTRAINT `accounts_users_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `accounts_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_archive` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `activity_log_log_name_index` (`log_name`),
  KEY `subject` (`subject_id`,`subject_type`),
  KEY `causer` (`causer_id`,`causer_type`)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `actors_related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_relation_id` int(11) NOT NULL,
  `actor_type` enum('customer','supplier','employee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `related_as` enum('global','actor_group','actor_id') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'global' COMMENT 'we use this column to identify the account relation for global actor (employees ,customers & suppliers) or for a group or for a custom actor',
  `related_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `advances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `deportation` enum('safe','bank') COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation` enum('deposit','withdrawal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(10,2) NOT NULL DEFAULT 0.00,
  `rest` double(10,2) NOT NULL DEFAULT 0.00,
  `employee_data_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `account_id` bigint(20) unsigned DEFAULT NULL,
  `locker_id` bigint(20) unsigned DEFAULT NULL,
  `salary_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advances_employee_data_id_foreign` (`employee_data_id`),
  KEY `advances_branch_id_foreign` (`branch_id`),
  CONSTRAINT `advances_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `adverse_restriction_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `accounts_tree_id` bigint(20) DEFAULT NULL,
  `fiscal_year` bigint(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `alternative_parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` bigint(20) unsigned NOT NULL,
  `alternative_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alternative_parts_part_id_foreign` (`part_id`),
  KEY `alternative_parts_alternative_id_foreign` (`alternative_id`),
  CONSTRAINT `alternative_parts_alternative_id_foreign` FOREIGN KEY (`alternative_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `alternative_parts_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `areas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `areas_city_id_foreign` (`city_id`),
  CONSTRAINT `areas_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `assets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(10,2) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `bank_accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iban` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swift_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `bank_exchange_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) NOT NULL,
  `from_bank_id` bigint(20) NOT NULL,
  `to_bank_id` bigint(20) NOT NULL,
  `cost_center_id` bigint(20) NOT NULL,
  `bank_receive_permission_id` bigint(20) DEFAULT NULL,
  `employee_id` bigint(20) NOT NULL,
  `permission_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_date` date NOT NULL,
  `amount` double(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `destination_type` enum('locker','bank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `bank_receive_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) NOT NULL,
  `bank_exchange_permission_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `cost_center_id` bigint(20) NOT NULL,
  `permission_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_date` date NOT NULL,
  `amount` double(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source_type` enum('locker','bank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bank',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `bank_transfer_pivots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bank_transfer_id` bigint(20) NOT NULL,
  `bank_receive_permission_id` bigint(20) NOT NULL,
  `bank_exchange_permission_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint(20) unsigned NOT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `area_id` bigint(20) unsigned DEFAULT NULL,
  `currency_id` bigint(20) unsigned DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_ar` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_en` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_card` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailbox_number` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `map` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_active` tinyint(1) NOT NULL DEFAULT 0,
  `vat_percent` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `shift_is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `branches_country_id_foreign` (`country_id`),
  KEY `branches_city_id_foreign` (`city_id`),
  KEY `branches_area_id_foreign` (`area_id`),
  KEY `branches_currency_id_foreign` (`currency_id`),
  CONSTRAINT `branches_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `branches_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `branches_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `branches_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `branches_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branches_roles_branch_id_foreign` (`branch_id`),
  KEY `branches_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `branches_roles_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `branches_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `capital_balances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) DEFAULT NULL,
  `balance` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `car_models` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `car_models_company_id_foreign` (`company_id`),
  CONSTRAINT `car_models_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `car_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `card_invoice_maintenance_detection` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `card_invoice_id` bigint(20) unsigned NOT NULL,
  `maintenance_detection_id` bigint(20) unsigned NOT NULL,
  `maintenance_type_id` bigint(20) unsigned NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `degree` tinyint(4) NOT NULL COMMENT ' 1 => low , 2 => average, 3 => high',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `card_invoice_maintenance_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `card_invoice_id` bigint(20) unsigned NOT NULL,
  `maintenance_type_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_after_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `card_invoice_type_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `card_invoice_type_id` bigint(20) unsigned NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `purchase_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_invoice_type_items_card_invoice_type_id_foreign` (`card_invoice_type_id`),
  CONSTRAINT `card_invoice_type_items_card_invoice_type_id_foreign` FOREIGN KEY (`card_invoice_type_id`) REFERENCES `card_invoice_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `card_invoice_type_items_employee_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) unsigned NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `percent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_invoice_type_items_employee_data_employee_id_foreign` (`employee_id`),
  KEY `card_invoice_type_items_employee_data_item_id_foreign` (`item_id`),
  CONSTRAINT `card_invoice_type_items_employee_data_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employee_data` (`id`) ON DELETE CASCADE,
  CONSTRAINT `card_invoice_type_items_employee_data_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `card_invoice_type_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `card_invoice_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `card_invoice_id` bigint(20) unsigned NOT NULL,
  `maintenance_detection_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_invoice_types_card_invoice_id_foreign` (`card_invoice_id`),
  CONSTRAINT `card_invoice_types_card_invoice_id_foreign` FOREIGN KEY (`card_invoice_id`) REFERENCES `card_invoices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `card_invoice_winch_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `card_invoice_type_id` bigint(20) unsigned NOT NULL,
  `branch_lat` double NOT NULL,
  `branch_long` double NOT NULL,
  `request_lat` double NOT NULL,
  `request_long` double NOT NULL,
  `distance` double(8,2) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `card_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_card_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` enum('cash','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_after_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `customer_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `customer_discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `customer_discount_status` tinyint(4) NOT NULL DEFAULT 0,
  `points_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `points_rule_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_invoices_work_card_id_foreign` (`work_card_id`),
  KEY `card_invoices_created_by_foreign` (`created_by`),
  CONSTRAINT `card_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `card_invoices_work_card_id_foreign` FOREIGN KEY (`work_card_id`) REFERENCES `work_cards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `cars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` bigint(20) unsigned DEFAULT NULL,
  `model_id` bigint(20) unsigned DEFAULT NULL,
  `company_id` bigint(20) unsigned DEFAULT NULL,
  `plate_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Chassis_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speedometer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `motor_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cars_barcode_unique` (`barcode`),
  KEY `cars_customer_id_foreign` (`customer_id`),
  CONSTRAINT `cars_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cities_country_id_foreign` (`country_id`),
  CONSTRAINT `cities_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `companies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `concession_executions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `concession_id` bigint(20) unsigned NOT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pending, late, finished',
  `notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `concession_executions_concession_id_foreign` (`concession_id`),
  CONSTRAINT `concession_executions_concession_id_foreign` FOREIGN KEY (`concession_id`) REFERENCES `concessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `concession_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `concession_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `part_price_id` bigint(20) unsigned NOT NULL,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `part_price_segment_id` bigint(20) unsigned DEFAULT NULL,
  `accepted_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `log_message` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `concession_libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `concession_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `concession_libraries_concession_id_foreign` (`concession_id`),
  CONSTRAINT `concession_libraries_concession_id_foreign` FOREIGN KEY (`concession_id`) REFERENCES `concessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `concession_type_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'add, withdrawal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `concession_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `concession_type_item_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `concessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pending, accepted, finished, rejected',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'add, withdrawal',
  `concession_type_id` bigint(20) unsigned NOT NULL,
  `concessionable_id` bigint(20) unsigned NOT NULL,
  `concessionable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `library_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `cost_centers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cost_centers_id` bigint(20) NOT NULL,
  `tree_level` int(11) NOT NULL DEFAULT 0,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `countries_currency_id_foreign` (`currency_id`),
  CONSTRAINT `countries_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `currencies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `customer_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `sales_discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_discount` decimal(10,0) DEFAULT NULL,
  `services_discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `services_discount` decimal(10,0) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_categories_branch_id_foreign` (`branch_id`),
  CONSTRAINT `customer_categories_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `customer_contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `phone_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `customer_libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `customer_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `branch_id` bigint(20) unsigned NOT NULL,
  `reject_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `customer_reservations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `event_date` date NOT NULL,
  `event_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` int(11) DEFAULT NULL,
  `commercial_register` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cars_number` int(11) NOT NULL DEFAULT 0,
  `balance_to` double NOT NULL DEFAULT 0,
  `balance_for` double NOT NULL DEFAULT 0,
  `tax_card` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responsible` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('person','company') COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `customer_category_id` bigint(20) unsigned DEFAULT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `area_id` bigint(20) unsigned DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `can_edit` tinyint(1) NOT NULL DEFAULT 0,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme` enum('red','violet','dark-blue','blue','light-blue','green','yellow','orange','chocolate','dark-green') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dark-blue',
  `points` bigint(20) NOT NULL DEFAULT 0,
  `tax_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `long` double DEFAULT NULL,
  `maximum_fund_on` decimal(8,2) NOT NULL DEFAULT 0.00,
  `library_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_branch_id_foreign` (`branch_id`),
  KEY `customers_customer_category_id_foreign` (`customer_category_id`),
  KEY `customers_country_id_foreign` (`country_id`),
  KEY `customers_city_id_foreign` (`city_id`),
  CONSTRAINT `customers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `customers_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `customers_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `customers_customer_category_id_foreign` FOREIGN KEY (`customer_category_id`) REFERENCES `customer_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `daily_restriction_tables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `daily_restriction_id` bigint(20) DEFAULT NULL,
  `accounts_tree_id` bigint(20) DEFAULT NULL,
  `debit_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `credit_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_tree_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_center_root_id` bigint(20) DEFAULT NULL,
  `cost_center_id` bigint(20) DEFAULT NULL,
  `cost_center_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `daily_restrictions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `restriction_number` bigint(20) DEFAULT NULL,
  `operation_number` bigint(20) DEFAULT NULL,
  `operation_date` date DEFAULT NULL,
  `debit_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `credit_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `records_number` int(11) NOT NULL DEFAULT 0,
  `auto_generated` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fiscal_year_id` bigint(20) DEFAULT NULL,
  `is_adverse` tinyint(1) NOT NULL DEFAULT 0,
  `reference_id` bigint(20) DEFAULT NULL,
  `reference_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `damaged_stock_employee_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `damaged_stock_id` bigint(20) unsigned NOT NULL,
  `employee_data_id` bigint(20) unsigned NOT NULL,
  `percent` decimal(8,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `damaged_stock_employee_data_damaged_stock_id_foreign` (`damaged_stock_id`),
  CONSTRAINT `damaged_stock_employee_data_damaged_stock_id_foreign` FOREIGN KEY (`damaged_stock_id`) REFERENCES `damaged_stocks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `damaged_stock_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `damaged_stock_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `store_id` bigint(20) unsigned NOT NULL,
  `part_price_id` bigint(20) unsigned NOT NULL,
  `part_price_segment_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `damaged_stock_items_damaged_stock_id_foreign` (`damaged_stock_id`),
  CONSTRAINT `damaged_stock_items_damaged_stock_id_foreign` FOREIGN KEY (`damaged_stock_id`) REFERENCES `damaged_stocks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `damaged_stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'natural' COMMENT 'natural, un_natural',
  `date` date NOT NULL,
  `time` time NOT NULL,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `discount_related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_relation_id` int(11) NOT NULL,
  `discount_type` enum('earned','permitted','points') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'earned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `employee_absences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `absence_days` int(11) NOT NULL DEFAULT 0,
  `absence_type` enum('vacation','absence') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'vacation',
  `date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `employee_attendances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('attendance','departure') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_data_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_attendances_employee_data_id_foreign` (`employee_data_id`),
  KEY `employee_attendances_branch_id_foreign` (`branch_id`),
  CONSTRAINT `employee_attendances_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `employee_attendances_employee_data_id_foreign` FOREIGN KEY (`employee_data_id`) REFERENCES `employee_data` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `employee_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Functional_class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date_id_number` date DEFAULT NULL,
  `start_date_assign` date DEFAULT NULL,
  `start_date_stay` date DEFAULT NULL,
  `end_date_stay` date DEFAULT NULL,
  `end_date_health` date DEFAULT NULL,
  `number_card_work` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `cv` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` tinyint(4) DEFAULT 0,
  `employee_setting_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `area_id` bigint(20) unsigned DEFAULT NULL,
  `national_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_data_email_unique` (`email`),
  KEY `employee_data_employee_setting_id_foreign` (`employee_setting_id`),
  KEY `employee_data_branch_id_foreign` (`branch_id`),
  KEY `employee_data_country_id_foreign` (`country_id`),
  KEY `employee_data_city_id_foreign` (`city_id`),
  KEY `employee_data_area_id_foreign` (`area_id`),
  KEY `employee_data_national_id_foreign` (`national_id`),
  CONSTRAINT `employee_data_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`),
  CONSTRAINT `employee_data_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `employee_data_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `employee_data_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `employee_data_employee_setting_id_foreign` FOREIGN KEY (`employee_setting_id`) REFERENCES `employee_settings` (`id`),
  CONSTRAINT `employee_data_national_id_foreign` FOREIGN KEY (`national_id`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `employee_delays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('delay','extra') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `number_of_hours` int(11) NOT NULL,
  `number_of_minutes` int(11) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_data_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_delays_employee_data_id_foreign` (`employee_data_id`),
  KEY `employee_delays_branch_id_foreign` (`branch_id`),
  CONSTRAINT `employee_delays_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `employee_delays_employee_data_id_foreign` FOREIGN KEY (`employee_data_id`) REFERENCES `employee_data` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `employee_reward_discounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('discount','reward') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` double NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_data_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_reward_discounts_employee_data_id_foreign` (`employee_data_id`),
  KEY `employee_reward_discounts_branch_id_foreign` (`branch_id`),
  CONSTRAINT `employee_reward_discounts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `employee_reward_discounts_employee_data_id_foreign` FOREIGN KEY (`employee_data_id`) REFERENCES `employee_data` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `employee_salaries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` bigint(20) NOT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `salary` double(10,2) NOT NULL DEFAULT 0.00,
  `insurances` double(10,2) NOT NULL DEFAULT 0.00,
  `allowances` double(10,2) NOT NULL DEFAULT 0.00,
  `advance_included` tinyint(1) NOT NULL DEFAULT 1,
  `date` date DEFAULT NULL,
  `deportation_method` enum('bank','locker') COLLATE utf8mb4_unicode_ci NOT NULL,
  `locker_id` bigint(20) DEFAULT NULL,
  `account_id` bigint(20) DEFAULT NULL,
  `employee_data` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pay_type` enum('cash','credit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `paid_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `rest_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `cost_center_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_salaries_branch_id_foreign` (`branch_id`),
  CONSTRAINT `employee_salaries_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `employee_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_attend` time NOT NULL,
  `time_leave` time NOT NULL,
  `daily_working_hours` int(11) NOT NULL DEFAULT 0,
  `annual_vocation_days` int(11) NOT NULL DEFAULT 0,
  `max_advance` bigint(20) NOT NULL,
  `amount_account` bigint(20) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `type_account` enum('work_card','days','month') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_absence` enum('discount_day','fixed_salary') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_absence_equal` int(11) NOT NULL,
  `hourly_extra` enum('hourly_extra','fixed_salary') COLLATE utf8mb4_unicode_ci NOT NULL,
  `hourly_extra_equal` int(11) NOT NULL,
  `hourly_delay` enum('hourly_delay','fixed_salary') COLLATE utf8mb4_unicode_ci NOT NULL,
  `hourly_delay_equal` int(11) NOT NULL,
  `saturday` tinyint(1) NOT NULL DEFAULT 0,
  `sunday` tinyint(1) NOT NULL DEFAULT 0,
  `monday` tinyint(1) NOT NULL DEFAULT 0,
  `tuesday` tinyint(1) NOT NULL DEFAULT 0,
  `wednesday` tinyint(1) NOT NULL DEFAULT 0,
  `thursday` tinyint(1) NOT NULL DEFAULT 0,
  `friday` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` bigint(20) unsigned NOT NULL,
  `shift_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `card_work_percent` double(10,2) DEFAULT NULL,
  `service_status` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `employee_settings_branch_id_foreign` (`branch_id`),
  KEY `employee_settings_shift_id_foreign` (`shift_id`),
  CONSTRAINT `employee_settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `expenses_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expense_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_seeder` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `expenses_items_expense_id_foreign` (`expense_id`),
  KEY `expenses_items_branch_id_foreign` (`branch_id`),
  CONSTRAINT `expenses_items_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `expenses_items_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `expenses_receipts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `receiver` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(8,2) NOT NULL,
  `deportation` enum('safe','bank') COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_type_id` bigint(20) unsigned NOT NULL,
  `expense_item_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `locker_id` bigint(20) unsigned DEFAULT NULL,
  `account_id` bigint(20) unsigned DEFAULT NULL,
  `purchase_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `sales_invoice_return_id` bigint(20) unsigned DEFAULT NULL,
  `advance_id` bigint(20) unsigned DEFAULT NULL,
  `employee_salary_id` bigint(20) unsigned DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_account_type` enum('employees','customers','suppliers') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_account_id` bigint(20) DEFAULT NULL,
  `payment_type` enum('check','cash','network') COLLATE utf8mb4_unicode_ci NOT NULL,
  `check_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_center_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_receipts_expense_type_id_foreign` (`expense_type_id`),
  KEY `expenses_receipts_expense_item_id_foreign` (`expense_item_id`),
  KEY `expenses_receipts_branch_id_foreign` (`branch_id`),
  KEY `expenses_receipts_locker_id_foreign` (`locker_id`),
  KEY `expenses_receipts_account_id_foreign` (`account_id`),
  KEY `expenses_receipts_purchase_invoice_id_foreign` (`purchase_invoice_id`),
  KEY `expenses_receipts_advance_id_foreign` (`advance_id`),
  KEY `expenses_receipts_employee_salary_id_foreign` (`employee_salary_id`),
  CONSTRAINT `expenses_receipts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `expenses_receipts_advance_id_foreign` FOREIGN KEY (`advance_id`) REFERENCES `advances` (`id`),
  CONSTRAINT `expenses_receipts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `expenses_receipts_employee_salary_id_foreign` FOREIGN KEY (`employee_salary_id`) REFERENCES `employee_salaries` (`id`),
  CONSTRAINT `expenses_receipts_expense_item_id_foreign` FOREIGN KEY (`expense_item_id`) REFERENCES `expenses_items` (`id`),
  CONSTRAINT `expenses_receipts_expense_type_id_foreign` FOREIGN KEY (`expense_type_id`) REFERENCES `expenses_types` (`id`),
  CONSTRAINT `expenses_receipts_locker_id_foreign` FOREIGN KEY (`locker_id`) REFERENCES `lockers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `expenses_receipts_purchase_invoice_id_foreign` FOREIGN KEY (`purchase_invoice_id`) REFERENCES `purchase_invoices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `expenses_revenues_related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_relation_id` int(11) NOT NULL,
  `related_as` enum('debit','credit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'debit',
  `type_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `expenses_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_seeder` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `expenses_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `expenses_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `fiscal_years` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `follow_ups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `work_card_id` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kilo_number` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `locker_exchange_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) NOT NULL,
  `from_locker_id` bigint(20) NOT NULL,
  `to_locker_id` bigint(20) NOT NULL,
  `cost_center_id` bigint(20) NOT NULL,
  `locker_receive_permission_id` bigint(20) DEFAULT NULL,
  `employee_id` bigint(20) NOT NULL,
  `permission_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_date` date NOT NULL,
  `amount` double(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `destination_type` enum('locker','bank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'locker',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `locker_receive_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) NOT NULL,
  `locker_exchange_permission_id` bigint(20) NOT NULL,
  `employee_id` bigint(20) NOT NULL,
  `cost_center_id` bigint(20) NOT NULL,
  `permission_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_date` date NOT NULL,
  `amount` double(10,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source_type` enum('locker','bank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'locker',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `locker_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `locker_id` bigint(20) unsigned NOT NULL,
  `account_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `type` enum('deposit','withdrawal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `locker_transactions_account_id_foreign` (`account_id`),
  KEY `locker_transactions_created_by_foreign` (`created_by`),
  KEY `locker_transactions_locker_id_foreign` (`locker_id`),
  KEY `locker_transactions_branch_id_foreign` (`branch_id`),
  CONSTRAINT `locker_transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `locker_transactions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `locker_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `locker_transactions_locker_id_foreign` FOREIGN KEY (`locker_id`) REFERENCES `lockers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `locker_transfer_pivots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `locker_transfer_id` bigint(20) NOT NULL,
  `locker_receive_permission_id` bigint(20) NOT NULL,
  `locker_exchange_permission_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `locker_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `locker_from_id` bigint(20) unsigned NOT NULL,
  `locker_to_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `locker_transfers_locker_from_id_foreign` (`locker_from_id`),
  KEY `locker_transfers_locker_to_id_foreign` (`locker_to_id`),
  KEY `locker_transfers_branch_id_foreign` (`branch_id`),
  KEY `locker_transfers_created_by_foreign` (`created_by`),
  CONSTRAINT `locker_transfers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `locker_transfers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `locker_transfers_locker_from_id_foreign` FOREIGN KEY (`locker_from_id`) REFERENCES `lockers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `locker_transfers_locker_to_id_foreign` FOREIGN KEY (`locker_to_id`) REFERENCES `lockers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `lockers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `special` tinyint(1) NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lockers_branch_id_foreign` (`branch_id`),
  CONSTRAINT `lockers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `lockers_banks_related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_relation_id` int(11) NOT NULL,
  `related_as` enum('locker','bank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'locker',
  `related_id` int(11) NOT NULL COMMENT 'we use this column for custom locker or bank',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `lockers_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `locker_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lockers_users_user_id_foreign` (`user_id`),
  KEY `lockers_users_locker_id_foreign` (`locker_id`),
  CONSTRAINT `lockers_users_locker_id_foreign` FOREIGN KEY (`locker_id`) REFERENCES `lockers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lockers_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `mail_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `customer_request_status` tinyint(1) NOT NULL DEFAULT 1,
  `customer_request_accept` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_request_reject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_request_status` tinyint(1) NOT NULL DEFAULT 1,
  `quotation_request_accept` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_request_reject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_request_pending` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_status` tinyint(1) NOT NULL DEFAULT 1,
  `sales_invoice_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_return_status` tinyint(1) NOT NULL DEFAULT 1,
  `sales_invoice_return_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_return_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_return_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_send_status` tinyint(1) NOT NULL DEFAULT 1,
  `work_card_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_payments_status` tinyint(1) NOT NULL DEFAULT 1,
  `sales_invoice_payments_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_payments_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_payments_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_return_payments_status` tinyint(1) NOT NULL DEFAULT 1,
  `sales_return_payments_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_return_payments_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_return_payments_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_payments_status` tinyint(1) NOT NULL DEFAULT 1,
  `work_card_payments_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_payments_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_payments_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_status` tinyint(1) NOT NULL DEFAULT 1,
  `work_card_status_pending` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_status_processing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_status_finished` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_follow_up_status` tinyint(1) NOT NULL DEFAULT 1,
  `car_follow_up_remember` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expenses_status` tinyint(1) NOT NULL DEFAULT 1,
  `expenses_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expenses_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expenses_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_status` tinyint(1) NOT NULL DEFAULT 1,
  `revenue_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `maintenance_detection_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_detection_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `maintenance_detection_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `maintenance_detections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `maintenance_type_id` bigint(20) unsigned NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `maintenance_detections_branch_id_foreign` (`branch_id`),
  KEY `maintenance_detections_maintenance_type_id_foreign` (`maintenance_type_id`),
  CONSTRAINT `maintenance_detections_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `maintenance_detections_maintenance_type_id_foreign` FOREIGN KEY (`maintenance_type_id`) REFERENCES `maintenance_detection_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=316 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `module_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `module_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module_permissions_permission_id_foreign` (`permission_id`),
  KEY `module_permissions_module_id_foreign` (`module_id`),
  CONSTRAINT `module_permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `module_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=332 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `money_permissions_related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_relation_id` int(11) NOT NULL,
  `money_gateway` enum('locker','bank') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'locker',
  `act_as` enum('exchange','receive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'exchange',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `notification_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `customer_request` tinyint(4) NOT NULL DEFAULT 1,
  `quotation_request` tinyint(4) NOT NULL DEFAULT 1,
  `work_card_status_to_user` tinyint(4) NOT NULL DEFAULT 1,
  `minimum_parts_request` tinyint(4) NOT NULL DEFAULT 1,
  `end_work_card_employee` tinyint(4) NOT NULL DEFAULT 1,
  `end_residence_employee` tinyint(4) NOT NULL DEFAULT 1,
  `end_medical_insurance_employee` tinyint(4) NOT NULL DEFAULT 1,
  `quotation_request_status` tinyint(4) NOT NULL DEFAULT 1,
  `sales_invoice` tinyint(4) NOT NULL DEFAULT 1,
  `return_sales_invoice` tinyint(4) NOT NULL DEFAULT 1,
  `work_card` tinyint(4) NOT NULL DEFAULT 1,
  `work_card_status_to_customer` tinyint(4) NOT NULL DEFAULT 1,
  `sales_invoice_payments` tinyint(4) NOT NULL DEFAULT 1,
  `return_sales_invoice_payments` tinyint(4) NOT NULL DEFAULT 1,
  `work_card_payments` tinyint(4) NOT NULL DEFAULT 1,
  `follow_up_cars` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `opening_balance_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `opening_balance_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `part_price_id` int(11) NOT NULL,
  `part_price_price_segment_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `default_unit_quantity` int(11) NOT NULL DEFAULT 0,
  `buy_price` double(10,2) NOT NULL DEFAULT 0.00,
  `store_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `opening_balances` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `serial_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operation_date` date NOT NULL,
  `operation_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_money` double(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purchase_invoice_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `part_libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `part_price_segments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `part_price_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `sales_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `maintenance_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `part_prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selling_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `purchase_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `less_selling_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `service_selling_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `less_service_selling_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `maximum_sale_amount` int(11) NOT NULL DEFAULT 0,
  `minimum_for_order` int(11) NOT NULL DEFAULT 0 COMMENT 'less quantity for new order',
  `biggest_percent_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `biggest_amount_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `last_selling_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `last_purchase_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `default_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `default_sales` tinyint(1) NOT NULL DEFAULT 0,
  `default_maintenance` tinyint(1) NOT NULL DEFAULT 0,
  `supplier_barcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `damage_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `part_prices_part_id_foreign` (`part_id`),
  CONSTRAINT `part_prices_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `part_spare_part` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` bigint(20) unsigned NOT NULL,
  `spare_part_type_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `part_store` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` bigint(20) unsigned NOT NULL,
  `store_id` bigint(20) unsigned NOT NULL,
  `quantity` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `part_taxes_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `part_id` bigint(20) unsigned NOT NULL,
  `taxes_fees_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `part_taxes_fees_part_id_foreign` (`part_id`),
  KEY `part_taxes_fees_taxes_fees_id_foreign` (`taxes_fees_id`),
  CONSTRAINT `part_taxes_fees_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `part_taxes_fees_taxes_fees_id_foreign` FOREIGN KEY (`taxes_fees_id`) REFERENCES `taxes_fees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spare_part_unit_id` bigint(20) unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL COMMENT ' false=>inActive, true=>Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `library_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `is_service` tinyint(1) NOT NULL DEFAULT 0,
  `part_in_store` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reviewable` tinyint(4) NOT NULL DEFAULT 0,
  `taxable` tinyint(4) NOT NULL DEFAULT 0,
  `suppliers_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parts_spare_part_unit_id_foreign` (`spare_part_unit_id`),
  CONSTRAINT `parts_spare_part_unit_id_foreign` FOREIGN KEY (`spare_part_unit_id`) REFERENCES `spare_part_units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `point_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `sales_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `sales_invoice_return_id` bigint(20) unsigned DEFAULT NULL,
  `card_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `log` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_amount` decimal(8,2) DEFAULT NULL,
  `setting_points` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `point_rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `text_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `point_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `points` bigint(20) NOT NULL DEFAULT 0,
  `amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_invoice_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `store_id` bigint(20) unsigned NOT NULL,
  `available_qty` int(11) NOT NULL,
  `purchase_qty` int(11) NOT NULL,
  `last_purchase_price` decimal(8,2) NOT NULL,
  `purchase_price` decimal(8,2) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_after_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_before_discount` double NOT NULL DEFAULT 0,
  `subtotal` decimal(8,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `part_price_id` bigint(20) unsigned DEFAULT NULL,
  `part_price_segment_id` bigint(20) unsigned DEFAULT NULL,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `purchase_invoice_items_purchase_invoice_id_foreign` (`purchase_invoice_id`),
  KEY `purchase_invoice_items_part_id_foreign` (`part_id`),
  KEY `purchase_invoice_items_store_id_foreign` (`store_id`),
  CONSTRAINT `purchase_invoice_items_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`),
  CONSTRAINT `purchase_invoice_items_purchase_invoice_id_foreign` FOREIGN KEY (`purchase_invoice_id`) REFERENCES `purchase_invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_invoice_items_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_invoice_items_taxes_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_invoice_item_id` bigint(20) unsigned NOT NULL,
  `taxes_fees_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_invoice_taxes_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_invoice_id` bigint(20) unsigned NOT NULL,
  `taxes_fees_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` enum('cash','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_items` int(11) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `discount_group_value` double(10,2) NOT NULL DEFAULT 0.00,
  `is_discount_group_added` tinyint(1) NOT NULL DEFAULT 0,
  `paid` double(10,2) NOT NULL DEFAULT 0.00,
  `remaining` double(10,2) NOT NULL DEFAULT 0.00,
  `is_returned` tinyint(1) NOT NULL DEFAULT 0,
  `discount_group_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_opening_balance` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `purchase_invoices_branch_id_foreign` (`branch_id`),
  CONSTRAINT `purchase_invoices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotation_executions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_quotation_id` bigint(20) unsigned NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pending, late, finished',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_executions_purchase_quotation_id_foreign` (`purchase_quotation_id`),
  CONSTRAINT `purchase_quotation_executions_purchase_quotation_id_foreign` FOREIGN KEY (`purchase_quotation_id`) REFERENCES `purchase_quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotation_item_taxes_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `tax_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_item_taxes_fees_item_id_foreign` (`item_id`),
  CONSTRAINT `purchase_quotation_item_taxes_fees_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `purchase_quotation_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotation_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_quotation_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `part_price_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount' COMMENT 'amount, percent',
  `total_after_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `part_price_segment_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_items_purchase_quotation_id_foreign` (`purchase_quotation_id`),
  CONSTRAINT `purchase_quotation_items_purchase_quotation_id_foreign` FOREIGN KEY (`purchase_quotation_id`) REFERENCES `purchase_quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotation_items_spare_parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `spare_part_id` bigint(20) unsigned NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_items_spare_parts_item_id_foreign` (`item_id`),
  CONSTRAINT `purchase_quotation_items_spare_parts_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `purchase_quotation_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotation_libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_quotation_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_libraries_purchase_quotation_id_foreign` (`purchase_quotation_id`),
  CONSTRAINT `purchase_quotation_libraries_purchase_quotation_id_foreign` FOREIGN KEY (`purchase_quotation_id`) REFERENCES `purchase_quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotation_supply_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supply_order_id` bigint(20) unsigned NOT NULL,
  `purchase_quotation_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_supply_orders_supply_order_id_foreign` (`supply_order_id`),
  CONSTRAINT `purchase_quotation_supply_orders_supply_order_id_foreign` FOREIGN KEY (`supply_order_id`) REFERENCES `supply_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotation_supply_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_quotation_id` bigint(20) unsigned NOT NULL,
  `supply_term_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_supply_terms_purchase_quotation_id_foreign` (`purchase_quotation_id`),
  CONSTRAINT `purchase_quotation_supply_terms_purchase_quotation_id_foreign` FOREIGN KEY (`purchase_quotation_id`) REFERENCES `purchase_quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotation_taxes_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_quotation_id` bigint(20) unsigned NOT NULL,
  `tax_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_quotation_taxes_fees_purchase_quotation_id_foreign` (`purchase_quotation_id`),
  CONSTRAINT `purchase_quotation_taxes_fees_purchase_quotation_id_foreign` FOREIGN KEY (`purchase_quotation_id`) REFERENCES `purchase_quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_quotations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `purchase_request_id` bigint(20) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending, accepted, rejected',
  `supply_date_from` date NOT NULL,
  `supply_date_to` date NOT NULL,
  `sub_total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount' COMMENT 'amount, percent',
  `total_after_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `library_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `additional_payments` decimal(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_request_executions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_request_id` bigint(20) unsigned NOT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pending, late, finished',
  `notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_request_executions_purchase_request_id_foreign` (`purchase_request_id`),
  CONSTRAINT `purchase_request_executions_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_request_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_request_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `part_price_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `approval_quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_request_items_purchase_request_id_foreign` (`purchase_request_id`),
  CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_request_items_spare_parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_request_item_id` bigint(20) unsigned NOT NULL,
  `spare_part_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_request_libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_request_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_request_libraries_purchase_request_id_foreign` (`purchase_request_id`),
  CONSTRAINT `purchase_request_libraries_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'under_processing',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `request_for` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requesting_party` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `library_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_return_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_returns_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `store_id` bigint(20) unsigned NOT NULL,
  `available_qty` int(11) NOT NULL,
  `purchase_qty` int(11) NOT NULL,
  `last_purchase_price` decimal(8,2) NOT NULL,
  `purchase_price` decimal(8,2) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_after_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_before_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_return_items_purchase_returns_id_foreign` (`purchase_returns_id`),
  KEY `purchase_return_items_store_id_foreign` (`store_id`),
  CONSTRAINT `purchase_return_items_purchase_returns_id_foreign` FOREIGN KEY (`purchase_returns_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_return_items_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `purchase_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` enum('cash','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_items` int(11) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) DEFAULT NULL,
  `total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `paid` double(10,2) NOT NULL DEFAULT 0.00,
  `remaining` double(10,2) NOT NULL DEFAULT 0.00,
  `supplier_discount_status` tinyint(4) NOT NULL DEFAULT 0,
  `supplier_discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `supplier_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `purchase_returns_branch_id_foreign` (`branch_id`),
  KEY `purchase_returns_purchase_invoice_id_foreign` (`purchase_invoice_id`),
  CONSTRAINT `purchase_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `purchase_returns_purchase_invoice_id_foreign` FOREIGN KEY (`purchase_invoice_id`) REFERENCES `purchase_invoices` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `quotation_type_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_type_id` bigint(20) unsigned NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `purchase_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_type_items_quotation_type_id_foreign` (`quotation_type_id`),
  CONSTRAINT `quotation_type_items_quotation_type_id_foreign` FOREIGN KEY (`quotation_type_id`) REFERENCES `quotation_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `quotation_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint(20) unsigned NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_types_quotation_id_foreign` (`quotation_id`),
  CONSTRAINT `quotation_types_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `quotation_winch_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_type_id` bigint(20) unsigned NOT NULL,
  `branch_lat` double NOT NULL,
  `branch_long` double NOT NULL,
  `request_lat` double NOT NULL,
  `request_long` double NOT NULL,
  `distance` double(8,2) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `quotations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `tax` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `rejected_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotations_customer_id_foreign` (`customer_id`),
  KEY `quotations_branch_id_foreign` (`branch_id`),
  KEY `quotations_created_by_foreign` (`created_by`),
  CONSTRAINT `quotations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `quotations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `revenue_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_ar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_en` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_seeder` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `revenue_items_revenue_id_foreign` (`revenue_id`),
  KEY `revenue_items_branch_id_foreign` (`branch_id`),
  CONSTRAINT `revenue_items_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `revenue_items_revenue_id_foreign` FOREIGN KEY (`revenue_id`) REFERENCES `revenue_types` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `revenue_receipts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `receiver` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `for` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` double NOT NULL,
  `deportation` enum('safe','bank') COLLATE utf8mb4_unicode_ci NOT NULL,
  `revenue_type_id` bigint(20) unsigned NOT NULL,
  `revenue_item_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `locker_id` bigint(20) unsigned DEFAULT NULL,
  `account_id` bigint(20) unsigned DEFAULT NULL,
  `purchase_return_id` bigint(20) unsigned DEFAULT NULL,
  `sales_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `advance_id` bigint(20) unsigned DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_invoice_id` bigint(20) unsigned DEFAULT NULL,
  `user_account_type` enum('employees','customers','suppliers') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_account_id` bigint(20) DEFAULT NULL,
  `payment_type` enum('check','cash','network') COLLATE utf8mb4_unicode_ci NOT NULL,
  `check_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_center_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revenue_receipts_revenue_type_id_foreign` (`revenue_type_id`),
  KEY `revenue_receipts_revenue_item_id_foreign` (`revenue_item_id`),
  KEY `revenue_receipts_branch_id_foreign` (`branch_id`),
  KEY `revenue_receipts_locker_id_foreign` (`locker_id`),
  KEY `revenue_receipts_account_id_foreign` (`account_id`),
  KEY `revenue_receipts_purchase_return_id_foreign` (`purchase_return_id`),
  KEY `revenue_receipts_advance_id_foreign` (`advance_id`),
  CONSTRAINT `revenue_receipts_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `revenue_receipts_advance_id_foreign` FOREIGN KEY (`advance_id`) REFERENCES `advances` (`id`),
  CONSTRAINT `revenue_receipts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `revenue_receipts_locker_id_foreign` FOREIGN KEY (`locker_id`) REFERENCES `lockers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `revenue_receipts_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`),
  CONSTRAINT `revenue_receipts_revenue_item_id_foreign` FOREIGN KEY (`revenue_item_id`) REFERENCES `revenue_items` (`id`),
  CONSTRAINT `revenue_receipts_revenue_type_id_foreign` FOREIGN KEY (`revenue_type_id`) REFERENCES `revenue_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `revenue_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_seeder` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `revenue_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `revenue_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `sales_invoice_item_returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sales_invoice_return_id` bigint(20) unsigned NOT NULL,
  `sales_invoice_item_id` bigint(20) unsigned NOT NULL,
  `purchase_invoice_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `available_qty` int(11) NOT NULL,
  `return_qty` int(11) NOT NULL,
  `last_selling_price` decimal(8,2) NOT NULL,
  `selling_price` decimal(8,2) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_invoice_item_returns_sales_invoice_return_id_foreign` (`sales_invoice_return_id`),
  KEY `sales_invoice_item_returns_purchase_invoice_id_foreign` (`purchase_invoice_id`),
  KEY `sales_invoice_item_returns_part_id_foreign` (`part_id`),
  CONSTRAINT `sales_invoice_item_returns_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`),
  CONSTRAINT `sales_invoice_item_returns_purchase_invoice_id_foreign` FOREIGN KEY (`purchase_invoice_id`) REFERENCES `purchase_invoices` (`id`),
  CONSTRAINT `sales_invoice_item_returns_sales_invoice_return_id_foreign` FOREIGN KEY (`sales_invoice_return_id`) REFERENCES `sales_invoice_returns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `sales_invoice_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sales_invoice_id` bigint(20) unsigned NOT NULL,
  `purchase_invoice_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `available_qty` int(11) NOT NULL,
  `sold_qty` int(11) NOT NULL,
  `last_selling_price` decimal(8,2) NOT NULL,
  `selling_price` decimal(8,2) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_invoice_items_sales_invoice_id_foreign` (`sales_invoice_id`),
  KEY `sales_invoice_items_purchase_invoice_id_foreign` (`purchase_invoice_id`),
  KEY `sales_invoice_items_part_id_foreign` (`part_id`),
  CONSTRAINT `sales_invoice_items_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `parts` (`id`),
  CONSTRAINT `sales_invoice_items_purchase_invoice_id_foreign` FOREIGN KEY (`purchase_invoice_id`) REFERENCES `purchase_invoices` (`id`),
  CONSTRAINT `sales_invoice_items_sales_invoice_id_foreign` FOREIGN KEY (`sales_invoice_id`) REFERENCES `sales_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `sales_invoice_returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `sales_invoice_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` enum('cash','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_items` int(11) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `tax` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `customer_discount_status` tinyint(4) NOT NULL DEFAULT 0,
  `customer_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `customer_discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `points_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `points_rule_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_invoice_returns_customer_id_foreign` (`customer_id`),
  KEY `sales_invoice_returns_sales_invoice_id_foreign` (`sales_invoice_id`),
  KEY `sales_invoice_returns_branch_id_foreign` (`branch_id`),
  KEY `sales_invoice_returns_created_by_foreign` (`created_by`),
  CONSTRAINT `sales_invoice_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `sales_invoice_returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `sales_invoice_returns_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `sales_invoice_returns_sales_invoice_id_foreign` FOREIGN KEY (`sales_invoice_id`) REFERENCES `sales_invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `sales_invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` enum('cash','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_items` int(11) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `tax` decimal(8,2) NOT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `total_after_discount` decimal(8,2) NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `customer_discount_status` tinyint(4) NOT NULL DEFAULT 0,
  `customer_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `customer_discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `points_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `points_rule_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sales_invoices_customer_id_foreign` (`customer_id`),
  KEY `sales_invoices_branch_id_foreign` (`branch_id`),
  KEY `sales_invoices_created_by_foreign` (`created_by`),
  CONSTRAINT `sales_invoices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `sales_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `sales_invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `service_packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_before_discount` double NOT NULL,
  `total_after_discount` double NOT NULL,
  `services_number` int(11) NOT NULL,
  `discount_type` enum('value','percent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` int(11) NOT NULL DEFAULT 0,
  `branch_id` bigint(20) unsigned NOT NULL,
  `service_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `number_of_hours` int(11) DEFAULT NULL,
  `number_of_min` int(11) DEFAULT NULL,
  `q` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_packages_branch_id_foreign` (`branch_id`),
  CONSTRAINT `service_packages_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `service_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `service_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` bigint(20) unsigned NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_ar` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `hours` int(11) NOT NULL DEFAULT 0,
  `minutes` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `services_type_id_foreign` (`type_id`),
  KEY `services_branch_id_foreign` (`branch_id`),
  CONSTRAINT `services_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `services_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `service_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sales_invoice_terms_ar` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sales_invoice_terms_en` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_terms_ar` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_terms_en` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_status` tinyint(4) NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `invoice_setting` tinyint(4) NOT NULL DEFAULT 1 COMMENT ' 0 => simple , 1 => advanced ',
  `filter_setting` tinyint(4) NOT NULL DEFAULT 1 COMMENT ' 0 => simple , 1 => advanced ',
  `sell_from_invoice_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'old',
  `lat` double NOT NULL DEFAULT 0,
  `long` double NOT NULL DEFAULT 0,
  `kilo_meter_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `quotation_terms_en` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_terms_ar` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_terms_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=>active, 0=> inActive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `settlement_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `settlement_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `store_id` bigint(20) unsigned NOT NULL,
  `part_price_id` bigint(20) unsigned NOT NULL,
  `part_price_segment_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `settlement_items_settlement_id_foreign` (`settlement_id`),
  CONSTRAINT `settlement_items_settlement_id_foreign` FOREIGN KEY (`settlement_id`) REFERENCES `settlements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `settlements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'positive' COMMENT 'negative, positive',
  `date` date NOT NULL,
  `time` time NOT NULL,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `shifts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_from` time NOT NULL,
  `end_from` time NOT NULL,
  `Saturday` int(11) NOT NULL DEFAULT 0,
  `sunday` int(11) NOT NULL DEFAULT 0,
  `monday` int(11) NOT NULL DEFAULT 0,
  `tuesday` int(11) NOT NULL DEFAULT 0,
  `wednesday` int(11) NOT NULL DEFAULT 0,
  `thursday` int(11) NOT NULL DEFAULT 0,
  `friday` int(11) NOT NULL DEFAULT 0,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `shifts_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `shift_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shifts_users_user_id_foreign` (`user_id`),
  KEY `shifts_users_shift_id_foreign` (`shift_id`),
  CONSTRAINT `shifts_users_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shifts_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `sms_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `customer_request_status` tinyint(1) NOT NULL DEFAULT 1,
  `customer_request_accept` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_request_reject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_request_status` tinyint(1) NOT NULL DEFAULT 1,
  `quotation_request_accept` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_request_reject` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_request_pending` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_status` tinyint(1) NOT NULL DEFAULT 1,
  `sales_invoice_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_return_status` tinyint(1) NOT NULL DEFAULT 1,
  `sales_invoice_return_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_return_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_return_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_send_status` tinyint(1) NOT NULL DEFAULT 1,
  `work_card_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_payments_status` tinyint(1) NOT NULL DEFAULT 1,
  `sales_invoice_payments_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_payments_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_invoice_payments_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_return_payments_status` tinyint(1) NOT NULL DEFAULT 1,
  `sales_return_payments_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_return_payments_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_return_payments_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_payments_status` tinyint(1) NOT NULL DEFAULT 1,
  `work_card_payments_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_payments_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_payments_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_status` tinyint(1) NOT NULL DEFAULT 1,
  `work_card_status_pending` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_status_processing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_card_status_finished` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `car_follow_up_status` tinyint(1) NOT NULL DEFAULT 1,
  `car_follow_up_remember` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expenses_status` tinyint(1) NOT NULL DEFAULT 1,
  `expenses_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expenses_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expenses_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_status` tinyint(1) NOT NULL DEFAULT 1,
  `revenue_create` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_edit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revenue_delete` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `spare_part_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `spare_parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `spare_part_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `store_employee_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id` bigint(20) unsigned DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `store_permission_related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_relation_id` int(11) NOT NULL,
  `permission_nature` enum('exchange','receive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'exchange',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `store_transfer_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `store_transfer_id` int(10) unsigned NOT NULL,
  `part_id` int(10) unsigned NOT NULL,
  `quantity` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `new_part_id` bigint(20) unsigned DEFAULT NULL,
  `part_price_id` bigint(20) unsigned NOT NULL,
  `part_price_segment_id` bigint(20) unsigned DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `store_transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transfer_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transfer_date` date NOT NULL,
  `store_from_id` int(10) unsigned NOT NULL,
  `store_to_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `branch_id` int(10) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `time` time NOT NULL,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_transfers_transfer_number_index` (`transfer_number`),
  KEY `store_transfers_transfer_date_index` (`transfer_date`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `stores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employees_ids` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `stores_related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_relation_id` int(11) NOT NULL,
  `related_as` enum('store','branch-stores') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'store',
  `related_id` int(11) NOT NULL COMMENT 'this column for store id or branch id depend on related_as value',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supplier_contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `phone_1` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supplier_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `discount_type` enum('amount','percent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `supplier_group_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_groups_branch_id_foreign` (`branch_id`),
  CONSTRAINT `supplier_groups_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supplier_libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `country_id` bigint(20) unsigned DEFAULT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `area_id` bigint(20) unsigned DEFAULT NULL,
  `phone_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('person','company') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commercial_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_card` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `funds_for` decimal(8,2) NOT NULL DEFAULT 0.00,
  `funds_on` decimal(8,2) NOT NULL DEFAULT 0.00,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `tax_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `long` double DEFAULT NULL,
  `maximum_fund_on` decimal(8,2) NOT NULL DEFAULT 0.00,
  `library_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_groups_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_groups_id` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_type` enum('supplier','contractor','both_together') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_branch_id_foreign` (`branch_id`),
  CONSTRAINT `suppliers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supply_order_executions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supply_order_id` bigint(20) unsigned NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pending, late, finished',
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supply_order_executions_supply_order_id_foreign` (`supply_order_id`),
  CONSTRAINT `supply_order_executions_supply_order_id_foreign` FOREIGN KEY (`supply_order_id`) REFERENCES `supply_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supply_order_item_taxes_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `tax_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `supply_order_item_taxes_fees_item_id_foreign` (`item_id`),
  CONSTRAINT `supply_order_item_taxes_fees_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `supply_order_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supply_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supply_order_id` bigint(20) unsigned NOT NULL,
  `part_id` bigint(20) unsigned NOT NULL,
  `part_price_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount' COMMENT 'amount, percent',
  `total_after_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supply_order_items_supply_order_id_foreign` (`supply_order_id`),
  CONSTRAINT `supply_order_items_supply_order_id_foreign` FOREIGN KEY (`supply_order_id`) REFERENCES `supply_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supply_order_libraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supply_order_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supply_order_libraries_supply_order_id_foreign` (`supply_order_id`),
  CONSTRAINT `supply_order_libraries_supply_order_id_foreign` FOREIGN KEY (`supply_order_id`) REFERENCES `supply_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supply_order_supply_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supply_order_id` bigint(20) unsigned NOT NULL,
  `supply_term_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `supply_order_supply_terms_supply_order_id_foreign` (`supply_order_id`),
  CONSTRAINT `supply_order_supply_terms_supply_order_id_foreign` FOREIGN KEY (`supply_order_id`) REFERENCES `supply_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supply_order_taxes_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supply_order_id` bigint(20) unsigned NOT NULL,
  `tax_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `supply_order_taxes_fees_supply_order_id_foreign` (`supply_order_id`),
  CONSTRAINT `supply_order_taxes_fees_supply_order_id_foreign` FOREIGN KEY (`supply_order_id`) REFERENCES `supply_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supply_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `purchase_request_id` bigint(20) unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'pending, accepted, rejected',
  `sub_total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount' COMMENT 'amount, percent',
  `total_after_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `additional_payments` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `library_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `supply_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `term_ar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '1 => active, 0 => inActive',
  `for_purchase_quotation` tinyint(4) NOT NULL COMMENT '1 => active, 0 => inActive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `taxes_fees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tax_type` enum('amount','percentage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `active_services` tinyint(1) NOT NULL DEFAULT 1,
  `active_invoices` tinyint(1) NOT NULL DEFAULT 1,
  `active_offers` tinyint(1) NOT NULL DEFAULT 1,
  `branch_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` double(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `active_purchase_invoice` tinyint(4) NOT NULL DEFAULT 0,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tax' COMMENT 'tax, additional_payments',
  `on_parts` tinyint(1) NOT NULL DEFAULT 0,
  `purchase_quotation` tinyint(4) NOT NULL DEFAULT 0,
  `execution_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'after_discount' COMMENT 'after_discount, before_discount',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `taxes_related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_relation_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `super_admin` tinyint(1) NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme` enum('red','violet','dark-blue','blue','light-blue','green','yellow','orange','chocolate','dark-green') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dark-blue',
  `is_admin_branch` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `work_cards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `card_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `car_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `receive_car_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=>not_received, 1=>received',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `receive_car_date` date NOT NULL,
  `receive_car_time` time NOT NULL,
  `delivery_car_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=>not_delivered, 1=>delivered',
  `delivery_car_date` date NOT NULL,
  `delivery_car_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_cards_customer_id_foreign` (`customer_id`),
  KEY `work_cards_created_by_foreign` (`created_by`),
  KEY `work_cards_branch_id_foreign` (`branch_id`),
  KEY `work_cards_car_id_foreign` (`car_id`),
  CONSTRAINT `work_cards_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `work_cards_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `work_cards_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('1','','','ميزانية','Budget','debit','الأصول','Assets','','','','1','1','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('2','','','ميزانية','Budget','debit','الخصوم و حقوق الملكية','Liabilities and equity','','','','2','1','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('3','','','قائمة الدخل','Income List','credit','المصروفات','Expense','','','','3','1','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('4','','','قائمة الدخل','Income List','credit','الإيرادات','Revenue','','','','4','1','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('5','','','ميزانية','Budget','debit','الأصول','Assets','','2021-05-01 23:06:01','2021-05-01 23:06:01','1','2','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('6','','','ميزانية','Budget','debit','الخصوم و حقوق الملكية','Liabilities and equity','','2021-05-01 23:06:01','2021-05-01 23:06:01','2','2','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('7','','','قائمة الدخل','Income List','credit','المصروفات','Expense','','2021-05-01 23:06:01','2021-05-01 23:06:01','3','2','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('8','','','قائمة الدخل','Income List','credit','الإيرادات','Revenue','','2021-05-01 23:06:01','2021-05-01 23:06:01','4','2','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('9','','','ميزانية','Budget','debit','الأصول','Assets','','2021-05-01 23:07:31','2021-05-01 23:07:31','1','3','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('10','','','ميزانية','Budget','debit','الخصوم و حقوق الملكية','Liabilities and equity','','2021-05-01 23:07:31','2021-05-01 23:07:31','2','3','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('11','','','قائمة الدخل','Income List','credit','المصروفات','Expense','','2021-05-01 23:07:31','2021-05-01 23:07:31','3','3','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('12','','','قائمة الدخل','Income List','credit','الإيرادات','Revenue','','2021-05-01 23:07:31','2021-05-01 23:07:31','4','3','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('13','','','ميزانية','Budget','debit','الأصول','Assets','','2021-05-07 03:18:29','2021-05-07 03:18:29','1','4','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('14','','','ميزانية','Budget','debit','الخصوم و حقوق الملكية','Liabilities and equity','','2021-05-07 03:18:29','2021-05-07 03:18:29','2','4','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('15','','','قائمة الدخل','Income List','credit','المصروفات','Expense','','2021-05-07 03:18:29','2021-05-07 03:18:29','3','4','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('16','','','قائمة الدخل','Income List','credit','الإيرادات','Revenue','','2021-05-07 03:18:29','2021-05-07 03:18:29','4','4','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('17','','','ميزانية','Budget','debit','الأصول','Assets','','2021-05-07 03:40:15','2021-05-07 03:40:15','1','5','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('18','','','ميزانية','Budget','debit','الخصوم و حقوق الملكية','Liabilities and equity','','2021-05-07 03:40:15','2021-05-07 03:40:15','2','5','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('19','','','قائمة الدخل','Income List','credit','المصروفات','Expense','','2021-05-07 03:40:15','2021-05-07 03:40:15','3','5','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('20','','','قائمة الدخل','Income List','credit','الإيرادات','Revenue','','2021-05-07 03:40:15','2021-05-07 03:40:15','4','5','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('21','','','ميزانية','Budget','debit','الأصول','Assets','','2021-05-07 03:40:53','2021-05-07 03:40:53','1','6','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('22','','','ميزانية','Budget','debit','الخصوم و حقوق الملكية','Liabilities and equity','','2021-05-07 03:40:53','2021-05-07 03:40:53','2','6','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('23','','','قائمة الدخل','Income List','credit','المصروفات','Expense','','2021-05-07 03:40:53','2021-05-07 03:40:53','3','6','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('24','','','قائمة الدخل','Income List','credit','الإيرادات','Revenue','','2021-05-07 03:40:53','2021-05-07 03:40:53','4','6','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('25','','','ميزانية','Budget','debit','الأصول','Assets','','2021-05-08 06:12:01','2021-05-08 06:12:01','1','7','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('26','','','ميزانية','Budget','debit','الخصوم و حقوق الملكية','Liabilities and equity','','2021-05-08 06:12:01','2021-05-08 06:12:01','2','7','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('27','','','قائمة الدخل','Income List','credit','المصروفات','Expense','','2021-05-08 06:12:01','2021-05-08 06:12:01','3','7','1');

INSERT INTO accounts_trees (`id`, `accounts_tree_id`, `tree_level`, `account_type_name_ar`, `account_type_name_en`, `account_nature`, `name_ar`, `name_en`, `deleted_at`, `created_at`, `updated_at`, `code`, `branch_id`, `custom_type`) VALUES 
('28','','','قائمة الدخل','Income List','credit','الإيرادات','Revenue','','2021-05-08 06:12:01','2021-05-08 06:12:01','4','7','1');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('3','default','This model has been updated','2','App\\Models\\Branch','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-16 04:18:46','2021-05-16 04:18:46','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('4','default','user superadmin logout date 2021-05-16 04:49:06','1','App\\Models\\User','1','App\\Models\\User','{\"logout date\":\"2021-05-16T02:49:06.948600Z\"}','2021-05-16 04:49:06','2021-05-16 04:49:06','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('5','default','user admin1 login date 2021-05-16 04:49:11','2','App\\Models\\User','2','App\\Models\\User','{\"login date\":\"2021-05-16T02:49:11.449145Z\"}','2021-05-16 04:49:11','2021-05-16 04:49:11','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('6','default','user admin1 logout date 2021-05-16 05:34:10','2','App\\Models\\User','2','App\\Models\\User','{\"logout date\":\"2021-05-16T03:34:10.834989Z\"}','2021-05-16 05:34:10','2021-05-16 05:34:10','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('7','default','user superadmin login date 2021-05-16 05:34:17','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-16T03:34:17.019446Z\"}','2021-05-16 05:34:17','2021-05-16 05:34:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('8','default','user superadmin login date 2021-05-16 08:14:03','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-16T06:14:03.864457Z\"}','2021-05-16 08:14:03','2021-05-16 08:14:03','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('9','default','user superadmin login date 2021-05-17 07:27:53','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-17T05:27:53.764728Z\"}','2021-05-17 07:27:53','2021-05-17 07:27:53','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('10','default','This model has been created','26','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"20000010\",\"date\":\"2021-05-17\",\"time\":\"08:08:00\",\"number_of_items\":0,\"total_after_discount\":\"5000.00\",\"is_discount_group_added\":0}}','2021-05-17 08:08:48','2021-05-17 08:08:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('11','default','This model has been updated','26','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"08:08:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"08:08\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-17 08:08:48','2021-05-17 08:08:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('12','default','This model has been deleted','26','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"20000010\",\"date\":\"2021-05-17\",\"time\":\"08:08:00\",\"number_of_items\":1,\"total_after_discount\":\"5000.00\",\"is_discount_group_added\":0}}','2021-05-17 08:09:11','2021-05-17 08:09:11','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('13','default','created','7','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 08:09:44','2021-05-17 08:09:44','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('14','default','deleted','7','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 08:11:18','2021-05-17 08:11:18','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('15','default','This model has been deleted','25','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"20000009\",\"date\":\"2021-05-12\",\"time\":\"06:33:00\",\"number_of_items\":1,\"total_after_discount\":\"5000.00\",\"is_discount_group_added\":0}}','2021-05-17 08:25:32','2021-05-17 08:25:32','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('16','default','created','8','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 16:21:45','2021-05-17 16:21:45','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('17','default','deleted','8','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 16:21:57','2021-05-17 16:21:57','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('18','default','created','9','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 16:22:05','2021-05-17 16:22:05','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('19','default','created','10','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 16:22:27','2021-05-17 16:22:27','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('20','default','created','11','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 16:22:45','2021-05-17 16:22:45','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('21','default','deleted','11','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 16:24:58','2021-05-17 16:24:58','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('22','default','deleted','9','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-17 16:25:03','2021-05-17 16:25:03','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('23','default','user superadmin login date 2021-05-18 07:00:48','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-18T05:00:48.597675Z\"}','2021-05-18 07:00:48','2021-05-18 07:00:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('24','default','user superadmin login date 2021-05-19 07:57:41','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-19T05:57:41.609141Z\"}','2021-05-19 07:57:41','2021-05-19 07:57:41','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('25','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-19 09:04:23','2021-05-19 09:04:23','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('26','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-19 09:04:41','2021-05-19 09:04:41','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('27','default','This model has been created','27','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"20000009\",\"date\":\"2021-05-19\",\"time\":\"10:01:00\",\"number_of_items\":0,\"total_after_discount\":\"2500.00\",\"is_discount_group_added\":0}}','2021-05-19 10:01:31','2021-05-19 10:01:31','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('28','default','This model has been updated','27','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"10:01:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"10:01\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 10:01:31','2021-05-19 10:01:31','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('29','default','This model has been deleted','27','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"20000009\",\"date\":\"2021-05-19\",\"time\":\"10:01:00\",\"number_of_items\":1,\"total_after_discount\":\"2500.00\",\"is_discount_group_added\":0}}','2021-05-19 10:01:43','2021-05-19 10:01:43','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('30','default','This model has been created','28','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"1\",\"date\":\"2021-05-19\",\"time\":\"10:03:00\",\"number_of_items\":0,\"total_after_discount\":\"2500.00\",\"is_discount_group_added\":0}}','2021-05-19 10:03:27','2021-05-19 10:03:27','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('31','default','This model has been updated','28','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"10:03:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"10:03\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 10:03:27','2021-05-19 10:03:27','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('32','default','This model has been created','29','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"2\",\"date\":\"2021-05-19\",\"time\":\"10:03:00\",\"number_of_items\":0,\"total_after_discount\":\"20000.00\",\"is_discount_group_added\":0}}','2021-05-19 10:03:40','2021-05-19 10:03:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('33','default','This model has been updated','29','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"10:03:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"10:03\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 10:03:40','2021-05-19 10:03:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('34','default','This model has been created','30','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"3\",\"date\":\"2021-05-19\",\"time\":\"10:03:00\",\"number_of_items\":0,\"total_after_discount\":\"52500.00\",\"is_discount_group_added\":0}}','2021-05-19 10:04:07','2021-05-19 10:04:07','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('35','default','This model has been updated','30','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"10:03:00\",\"number_of_items\":3,\"is_discount_group_added\":0},\"old\":{\"time\":\"10:03\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 10:04:07','2021-05-19 10:04:07','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('36','default','This model has been created','31','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"4\",\"date\":\"2021-05-19\",\"time\":\"10:04:00\",\"number_of_items\":0,\"total_after_discount\":\"107000.00\",\"is_discount_group_added\":0}}','2021-05-19 10:04:38','2021-05-19 10:04:38','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('37','default','This model has been updated','31','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"10:04:00\",\"number_of_items\":3,\"is_discount_group_added\":0},\"old\":{\"time\":\"10:04\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 10:04:38','2021-05-19 10:04:38','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('38','default','This model has been created','32','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"5\",\"date\":\"2021-05-19\",\"time\":\"10:56:00\",\"number_of_items\":0,\"total_after_discount\":\"25000.00\",\"is_discount_group_added\":0}}','2021-05-19 10:56:17','2021-05-19 10:56:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('39','default','This model has been updated','32','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"10:56:00\",\"number_of_items\":2,\"is_discount_group_added\":0},\"old\":{\"time\":\"10:56\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 10:56:17','2021-05-19 10:56:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('40','default','This model has been deleted','28','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"1\",\"date\":\"2021-05-19\",\"time\":\"10:03:00\",\"number_of_items\":1,\"total_after_discount\":\"2500.00\",\"is_discount_group_added\":0}}','2021-05-19 11:10:17','2021-05-19 11:10:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('41','default','This model has been deleted','29','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"2\",\"date\":\"2021-05-19\",\"time\":\"10:03:00\",\"number_of_items\":1,\"total_after_discount\":\"20000.00\",\"is_discount_group_added\":0}}','2021-05-19 11:10:17','2021-05-19 11:10:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('42','default','This model has been deleted','30','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"3\",\"date\":\"2021-05-19\",\"time\":\"10:03:00\",\"number_of_items\":3,\"total_after_discount\":\"52500.00\",\"is_discount_group_added\":0}}','2021-05-19 11:10:17','2021-05-19 11:10:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('43','default','This model has been deleted','31','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"4\",\"date\":\"2021-05-19\",\"time\":\"10:04:00\",\"number_of_items\":3,\"total_after_discount\":\"107000.00\",\"is_discount_group_added\":0}}','2021-05-19 11:10:17','2021-05-19 11:10:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('44','default','This model has been deleted','32','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"5\",\"date\":\"2021-05-19\",\"time\":\"10:56:00\",\"number_of_items\":2,\"total_after_discount\":\"25000.00\",\"is_discount_group_added\":0}}','2021-05-19 11:10:17','2021-05-19 11:10:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('45','default','This model has been created','33','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"1\",\"date\":\"2021-05-19\",\"time\":\"11:22:00\",\"number_of_items\":0,\"total_after_discount\":\"93000.00\",\"is_discount_group_added\":0}}','2021-05-19 11:22:46','2021-05-19 11:22:46','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('46','default','This model has been updated','33','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"11:22:00\",\"number_of_items\":3,\"is_discount_group_added\":0},\"old\":{\"time\":\"11:22\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 11:22:46','2021-05-19 11:22:46','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('47','default','This model has been created','34','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"2\",\"date\":\"2021-05-19\",\"time\":\"11:22:00\",\"number_of_items\":0,\"total_after_discount\":\"62000.00\",\"is_discount_group_added\":0}}','2021-05-19 11:23:26','2021-05-19 11:23:26','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('48','default','This model has been updated','34','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"11:22:00\",\"number_of_items\":3,\"is_discount_group_added\":0},\"old\":{\"time\":\"11:22\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 11:23:26','2021-05-19 11:23:26','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('49','default','This model has been created','35','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"3\",\"date\":\"2021-05-19\",\"time\":\"11:23:00\",\"number_of_items\":0,\"total_after_discount\":\"42500.00\",\"is_discount_group_added\":0}}','2021-05-19 11:24:24','2021-05-19 11:24:24','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('50','default','This model has been updated','35','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"11:23:00\",\"number_of_items\":3,\"is_discount_group_added\":0},\"old\":{\"time\":\"11:23\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-19 11:24:24','2021-05-19 11:24:24','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('51','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":10},\"old\":{\"quantity\":0}}','2021-05-19 11:26:31','2021-05-19 11:26:31','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('52','default','This model has been updated','5','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":11},\"old\":{\"quantity\":0}}','2021-05-19 11:26:31','2021-05-19 11:26:31','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('53','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":12},\"old\":{\"quantity\":0}}','2021-05-19 11:26:31','2021-05-19 11:26:31','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('54','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":22},\"old\":{\"quantity\":10}}','2021-05-19 11:26:45','2021-05-19 11:26:45','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('55','default','This model has been updated','5','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":13},\"old\":{\"quantity\":11}}','2021-05-19 11:26:45','2021-05-19 11:26:45','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('56','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":24},\"old\":{\"quantity\":12}}','2021-05-19 11:26:45','2021-05-19 11:26:45','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('57','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":27},\"old\":{\"quantity\":22}}','2021-05-19 11:26:59','2021-05-19 11:26:59','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('58','default','This model has been updated','5','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":18},\"old\":{\"quantity\":13}}','2021-05-19 11:26:59','2021-05-19 11:26:59','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('59','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":29},\"old\":{\"quantity\":24}}','2021-05-19 11:26:59','2021-05-19 11:26:59','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('61','default','This model has been updated','1','App\\Models\\User','1','App\\Models\\User','{\"attributes\":{\"email\":\"superadminsuperadmin@superadminsuperadmin.com\"},\"old\":{\"email\":\"superadmin@superadmin.com\"}}','2021-05-19 14:33:19','2021-05-19 14:33:19','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('62','default','This model has been updated','1','App\\Models\\User','1','App\\Models\\User','{\"attributes\":{\"email\":\"superadmin@superadmin.com\"},\"old\":{\"email\":\"superadminsuperadmin@superadminsuperadmin.com\"}}','2021-05-19 14:34:11','2021-05-19 14:34:11','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('63','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\"},\"old\":{\"name_ar\":\"\\u0635\\u0646\\u0641 3\"}}','2021-05-19 14:34:41','2021-05-19 14:34:41','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('64','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\"},\"old\":{\"name_ar\":\"\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\"}}','2021-05-19 14:35:35','2021-05-19 14:35:35','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('65','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0635\\u0646\\u0641 3\"},\"old\":{\"name_ar\":\"\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\\u0635\\u0646\\u0641 3\"}}','2021-05-19 14:36:09','2021-05-19 14:36:09','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('66','default','user superadmin login date 2021-05-20 06:02:11','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-20T04:02:11.536736Z\"}','2021-05-20 06:02:11','2021-05-20 06:02:11','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('67','default','This model has been created','37','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"4\",\"date\":\"2021-05-20\",\"time\":\"06:26:00\",\"number_of_items\":0,\"total_after_discount\":\"38000.00\",\"is_discount_group_added\":0}}','2021-05-20 06:27:20','2021-05-20 06:27:20','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('68','default','This model has been updated','37','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"06:26:00\",\"number_of_items\":2,\"is_discount_group_added\":0},\"old\":{\"time\":\"06:26\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-20 06:27:20','2021-05-20 06:27:20','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('69','default','This model has been updated','37','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"total_after_discount\":\"10500.00\"},\"old\":{\"total_after_discount\":\"38000.00\"}}','2021-05-20 06:27:35','2021-05-20 06:27:35','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('70','default','This model has been deleted','37','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"4\",\"date\":\"2021-05-20\",\"time\":\"06:26:00\",\"number_of_items\":2,\"total_after_discount\":\"10500.00\",\"is_discount_group_added\":0}}','2021-05-20 06:27:41','2021-05-20 06:27:41','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('71','default','This model has been created','38','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"4\",\"date\":\"2021-05-20\",\"time\":\"06:27:00\",\"number_of_items\":0,\"total_after_discount\":\"38000.00\",\"is_discount_group_added\":0}}','2021-05-20 06:28:22','2021-05-20 06:28:22','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('72','default','This model has been updated','38','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"06:27:00\",\"number_of_items\":2,\"is_discount_group_added\":0},\"old\":{\"time\":\"06:27\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-20 06:28:22','2021-05-20 06:28:22','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('73','default','This model has been updated','38','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"total_after_discount\":\"13000.00\"},\"old\":{\"total_after_discount\":\"38000.00\"}}','2021-05-20 06:28:46','2021-05-20 06:28:46','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('74','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":26},\"old\":{\"quantity\":27}}','2021-05-20 06:31:42','2021-05-20 06:31:42','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('75','default','This model has been updated','5','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":17},\"old\":{\"quantity\":18}}','2021-05-20 06:31:42','2021-05-20 06:31:42','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('76','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":27},\"old\":{\"quantity\":26}}','2021-05-20 06:32:03','2021-05-20 06:32:03','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('77','default','This model has been updated','5','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":18},\"old\":{\"quantity\":17}}','2021-05-20 06:32:03','2021-05-20 06:32:03','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('78','default','This model has been created','39','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"5\",\"date\":\"2021-05-20\",\"time\":\"06:38:00\",\"number_of_items\":0,\"total_after_discount\":\"5000.00\",\"is_discount_group_added\":0}}','2021-05-20 06:38:56','2021-05-20 06:38:56','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('79','default','This model has been updated','39','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"06:38:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"06:38\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-20 06:38:56','2021-05-20 06:38:56','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('80','default','This model has been created','40','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"6\",\"date\":\"2021-05-20\",\"time\":\"09:58:00\",\"number_of_items\":0,\"total_after_discount\":\"5000.00\",\"is_discount_group_added\":0}}','2021-05-20 09:58:49','2021-05-20 09:58:49','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('81','default','This model has been updated','40','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"09:58:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"09:58\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-20 09:58:49','2021-05-20 09:58:49','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('82','default','This model has been updated','40','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-20 09:59:02','2021-05-20 09:59:02','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('83','default','user superadmin login date 2021-05-21 11:58:57','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-21T09:58:57.720197Z\"}','2021-05-21 11:58:57','2021-05-21 11:58:57','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('84','default','user superadmin login date 2021-05-21 16:50:49','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-21T14:50:49.543000Z\"}','2021-05-21 16:50:49','2021-05-21 16:50:49','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('85','default','This model has been updated','1','App\\Models\\Branch','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-21 18:16:23','2021-05-21 18:16:23','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('86','default','This model has been updated','1','App\\Models\\Branch','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-21 18:16:43','2021-05-21 18:16:43','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('87','default','user superadmin login date 2021-05-23 14:07:32','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-23T12:07:32.194397Z\"}','2021-05-23 14:07:32','2021-05-23 14:07:32','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('88','default','user superadmin logout date 2021-05-23 14:07:40','1','App\\Models\\User','1','App\\Models\\User','{\"logout date\":\"2021-05-23T12:07:40.446316Z\"}','2021-05-23 14:07:40','2021-05-23 14:07:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('89','default','user superadmin login date 2021-05-23 14:07:54','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-23T12:07:54.709130Z\"}','2021-05-23 14:07:54','2021-05-23 14:07:54','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('90','default','user superadmin login date 2021-05-23 17:28:41','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-23T15:28:41.594396Z\"}','2021-05-23 17:28:41','2021-05-23 17:28:41','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('91','default','created','12','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-23 19:26:14','2021-05-23 19:26:14','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('92','default','updated','12','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-23 19:26:33','2021-05-23 19:26:33','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('93','default','user admin1 login date 2021-05-23 19:42:15','2','App\\Models\\User','2','App\\Models\\User','{\"login date\":\"2021-05-23T17:42:15.247213Z\"}','2021-05-23 19:42:15','2021-05-23 19:42:15','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('94','default','user superadmin login date 2021-05-24 09:21:56','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-24T07:21:56.812498Z\"}','2021-05-24 09:21:56','2021-05-24 09:21:56','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('95','default','user superadmin login date 2021-05-24 20:58:02','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-24T18:58:02.967216Z\"}','2021-05-24 20:58:02','2021-05-24 20:58:02','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('96','default','user superadmin login date 2021-05-24 23:12:39','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-24T21:12:39.889544Z\"}','2021-05-24 23:12:39','2021-05-24 23:12:39','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('97','default','user superadmin login date 2021-05-25 11:19:00','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-25T09:19:00.117392Z\"}','2021-05-25 11:19:00','2021-05-25 11:19:00','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('98','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0635\\u0646\\u0641 1 \\u0635\\u0646\\u0641 1 \\u0635\\u0646\\u0641 1 \\u0635\\u0646\\u0641 1 11111\"},\"old\":{\"name_ar\":\"\\u0635\\u0646\\u0641 1\"}}','2021-05-25 21:03:43','2021-05-25 21:03:43','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('99','default','user superadmin login date 2021-05-26 10:59:43','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-26T08:59:43.752827Z\"}','2021-05-26 10:59:43','2021-05-26 10:59:43','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('100','default','This model has been updated','8','App\\Models\\Supplier','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-26 11:13:21','2021-05-26 11:13:21','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('101','default','updated','1','App\\Models\\Customer','1','App\\Models\\User','[]','2021-05-26 11:13:55','2021-05-26 11:13:55','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('102','default','updated','1','App\\Models\\Customer','1','App\\Models\\User','[]','2021-05-26 11:13:55','2021-05-26 11:13:55','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('103','default','user admin1 login date 2021-05-26 23:06:47','2','App\\Models\\User','2','App\\Models\\User','{\"login date\":\"2021-05-26T21:06:47.709525Z\"}','2021-05-26 23:06:47','2021-05-26 23:06:47','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('104','default','user superadmin login date 2021-05-28 14:23:40','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-28T12:23:40.312317Z\"}','2021-05-28 14:23:40','2021-05-28 14:23:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('105','default','This model has been deleted','33','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"1\",\"date\":\"2021-05-19\",\"time\":\"11:22:00\",\"number_of_items\":3,\"total_after_discount\":\"93000.00\",\"is_discount_group_added\":0}}','2021-05-28 15:18:43','2021-05-28 15:18:43','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('106','default','This model has been deleted','34','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"2\",\"date\":\"2021-05-19\",\"time\":\"11:22:00\",\"number_of_items\":3,\"total_after_discount\":\"62000.00\",\"is_discount_group_added\":0}}','2021-05-28 15:18:48','2021-05-28 15:18:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('107','default','This model has been deleted','35','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"3\",\"date\":\"2021-05-19\",\"time\":\"11:23:00\",\"number_of_items\":3,\"total_after_discount\":\"42500.00\",\"is_discount_group_added\":0}}','2021-05-28 15:18:48','2021-05-28 15:18:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('108','default','This model has been deleted','38','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"4\",\"date\":\"2021-05-20\",\"time\":\"06:27:00\",\"number_of_items\":2,\"total_after_discount\":\"13000.00\",\"is_discount_group_added\":0}}','2021-05-28 15:18:48','2021-05-28 15:18:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('109','default','This model has been deleted','39','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"5\",\"date\":\"2021-05-20\",\"time\":\"06:38:00\",\"number_of_items\":1,\"total_after_discount\":\"5000.00\",\"is_discount_group_added\":0}}','2021-05-28 15:18:48','2021-05-28 15:18:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('110','default','This model has been deleted','40','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"6\",\"date\":\"2021-05-20\",\"time\":\"09:58:00\",\"number_of_items\":1,\"total_after_discount\":\"5000.00\",\"is_discount_group_added\":0}}','2021-05-28 15:18:48','2021-05-28 15:18:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('111','default','user superadmin login date 2021-05-28 20:13:57','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-28T18:13:57.794304Z\"}','2021-05-28 20:13:57','2021-05-28 20:13:57','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('112','default','deleted','12','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:20:23','2021-05-28 20:20:23','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('113','default','deleted','3','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:20:29','2021-05-28 20:20:29','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('114','default','deleted','6','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:20:39','2021-05-28 20:20:39','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('115','default','deleted','2','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:20:43','2021-05-28 20:20:43','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('116','default','deleted','10','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:20:54','2021-05-28 20:20:54','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('117','default','deleted','5','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:21:00','2021-05-28 20:21:00','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('118','default','deleted','4','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:21:05','2021-05-28 20:21:05','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('119','default','deleted','1','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:21:08','2021-05-28 20:21:08','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('120','default','created','13','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:25:48','2021-05-28 20:25:48','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('121','default','created','14','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:26:05','2021-05-28 20:26:05','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('122','default','created','15','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:26:38','2021-05-28 20:26:38','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('123','default','updated','13','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:26:57','2021-05-28 20:26:57','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('124','default','updated','14','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:27:03','2021-05-28 20:27:03','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('125','default','updated','15','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:27:10','2021-05-28 20:27:10','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('126','default','created','16','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:53:09','2021-05-28 20:53:09','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('127','default','created','17','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:53:51','2021-05-28 20:53:51','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('128','default','created','18','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:56:32','2021-05-28 20:56:32','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('129','default','created','19','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:58:00','2021-05-28 20:58:00','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('130','default','created','20','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:58:38','2021-05-28 20:58:38','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('131','default','created','21','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:59:02','2021-05-28 20:59:02','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('132','default','deleted','18','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 20:59:47','2021-05-28 20:59:47','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('133','default','created','22','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:00:17','2021-05-28 21:00:17','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('134','default','created','23','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:00:34','2021-05-28 21:00:34','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('135','default','created','24','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:01:09','2021-05-28 21:01:09','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('136','default','created','25','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:01:57','2021-05-28 21:01:57','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('137','default','created','26','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:02:40','2021-05-28 21:02:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('138','default','created','27','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:02:58','2021-05-28 21:02:58','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('139','default','created','28','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:03:31','2021-05-28 21:03:31','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('140','default','created','29','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:03:54','2021-05-28 21:03:54','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('141','default','deleted','15','App\\Models\\SparePart','1','App\\Models\\User','[]','2021-05-28 21:04:26','2021-05-28 21:04:26','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('142','default','This model has been created','1','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0633\\u0644\\u0643 \\u0633\\u0648\\u064a\\u062f\\u064a\\u0643\\u0633 2\\u0645\\u0645\",\"name_en\":\"Swedex wire 2 mm\",\"quantity\":0}}','2021-05-28 21:40:08','2021-05-28 21:40:08','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('143','default','This model has been created','2','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0633\\u0644\\u0643 \\u0633\\u0648\\u064a\\u062f\\u064a\\u0643\\u0633 16\\u0645\\u0645\",\"name_en\":\"Swedex wire 16 mm\",\"quantity\":0}}','2021-05-28 22:17:29','2021-05-28 22:17:29','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('144','default','This model has been created','3','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0642\\u0627\\u0637\\u0639 16\\u0627\\u062d\\u0627\\u062f\\u064a \\u0627\\u0645\\u0628\\u064a\\u0631 \\u0628\\u062a\\u0634\\u064a\\u0646\\u0648\",\"name_en\":\"Circuit breaker 16 mono amp chino\",\"quantity\":0}}','2021-05-28 22:51:57','2021-05-28 22:51:57','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('145','default','This model has been created','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0642\\u0627\\u0637\\u0639 \\u0627\\u062d\\u0627\\u062f\\u064a 32 \\u0627\\u0645\\u0628\\u064a\\u0631 \\u0628\\u062a\\u0634\\u064a\\u0646\\u0648\",\"name_en\":\"Bicino 32 amp mono circuit breaker\",\"quantity\":0}}','2021-05-28 22:57:26','2021-05-28 22:57:26','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('146','default','This model has been created','5','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0642\\u0627\\u0637\\u0639 \\u0627\\u062d\\u0627\\u062f\\u064a 32 \\u0627\\u0645\\u0628\\u064a\\u0631 \\u0641\\u064a\\u0646\\u0648\\u0633\",\"name_en\":\"Single circuit breaker 32 amp Venus\",\"quantity\":0}}','2021-05-28 23:19:13','2021-05-28 23:19:13','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('147','default','This model has been created','6','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0642\\u0627\\u0637\\u0639 16\\u0627\\u062d\\u0627\\u062f\\u064a \\u0627\\u0645\\u0628\\u064a\\u0631 \\u0641\\u064a\\u0646\\u0648\\u0633\",\"name_en\":\"16-ampere amperage circuit breaker of Venus\",\"quantity\":0}}','2021-05-28 23:20:21','2021-05-28 23:20:21','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('148','default','This model has been created','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u062d\\u0648\\u0636 \\u0627\\u064a\\u0646\\u0648\\u0641\\u0627 \\u0645\\u0635\\u0631\",\"name_en\":\"Basin Innova Egypt\",\"quantity\":0}}','2021-05-28 23:23:06','2021-05-28 23:23:06','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('149','default','This model has been created','8','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0628\\u0627\\u0646\\u064a\\u0648 \\u0648\\u0633\\u0637 \\u062c\\u062f\\u064a\\u062f\",\"name_en\":\"New center bathtub\",\"quantity\":0}}','2021-05-29 00:19:31','2021-05-29 00:19:31','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('150','default','This model has been created','9','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u062e\\u0644\\u0627\\u0637 \\u062f\\u0634\",\"name_en\":\"Shower mixer\",\"quantity\":0}}','2021-05-29 00:20:21','2021-05-29 00:20:21','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('151','default','This model has been created','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u062e\\u0644\\u0627\\u0637 \\u0645\\u0637\\u0628\\u062e\",\"name_en\":\"Kitchen mixer\",\"quantity\":0}}','2021-05-29 00:21:23','2021-05-29 00:21:23','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('152','default','user superadmin login date 2021-05-29 14:01:57','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-29T12:01:57.270915Z\"}','2021-05-29 14:01:57','2021-05-29 14:01:57','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('153','default','This model has been created','41','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"1\",\"date\":\"2021-05-29\",\"time\":\"14:09:00\",\"number_of_items\":0,\"total_after_discount\":\"10000.00\",\"is_discount_group_added\":0}}','2021-05-29 14:09:34','2021-05-29 14:09:34','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('154','default','This model has been updated','41','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"14:09:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"14:09\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-29 14:09:34','2021-05-29 14:09:34','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('155','default','This model has been created','42','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"2\",\"date\":\"2021-05-29\",\"time\":\"14:09:00\",\"number_of_items\":0,\"total_after_discount\":\"5000.00\",\"is_discount_group_added\":0}}','2021-05-29 14:09:51','2021-05-29 14:09:51','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('156','default','This model has been updated','42','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"14:09:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"14:09\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-29 14:09:51','2021-05-29 14:09:51','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('157','default','This model has been created','43','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"3\",\"date\":\"2021-05-29\",\"time\":\"14:09:00\",\"number_of_items\":0,\"total_after_discount\":\"7500.00\",\"is_discount_group_added\":0}}','2021-05-29 14:10:07','2021-05-29 14:10:07','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('158','default','This model has been updated','43','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"14:09:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"14:09\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-29 14:10:07','2021-05-29 14:10:07','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('159','default','This model has been created','44','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"4\",\"date\":\"2021-05-29\",\"time\":\"14:10:00\",\"number_of_items\":0,\"total_after_discount\":\"12000.00\",\"is_discount_group_added\":0}}','2021-05-29 14:10:32','2021-05-29 14:10:32','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('160','default','This model has been updated','44','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"14:10:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"14:10\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-29 14:10:32','2021-05-29 14:10:32','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('161','default','This model has been created','45','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"5\",\"date\":\"2021-05-29\",\"time\":\"14:10:00\",\"number_of_items\":0,\"total_after_discount\":\"13000.00\",\"is_discount_group_added\":0}}','2021-05-29 14:10:49','2021-05-29 14:10:49','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('162','default','This model has been updated','45','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"14:10:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"14:10\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-29 14:10:49','2021-05-29 14:10:49','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('163','default','This model has been created','46','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"6\",\"date\":\"2021-05-29\",\"time\":\"14:10:00\",\"number_of_items\":0,\"total_after_discount\":\"24000.00\",\"is_discount_group_added\":0}}','2021-05-29 14:11:09','2021-05-29 14:11:09','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('164','default','This model has been updated','46','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"14:10:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"14:10\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-05-29 14:11:09','2021-05-29 14:11:09','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('165','default','This model has been updated','1','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":10},\"old\":{\"quantity\":0}}','2021-05-29 14:12:11','2021-05-29 14:12:11','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('166','default','This model has been updated','6','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":20},\"old\":{\"quantity\":0}}','2021-05-29 14:12:23','2021-05-29 14:12:23','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('167','default','This model has been updated','9','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":15},\"old\":{\"quantity\":0}}','2021-05-29 14:16:22','2021-05-29 14:16:22','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('168','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":4},\"old\":{\"quantity\":0}}','2021-05-29 14:16:40','2021-05-29 14:16:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('169','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":1},\"old\":{\"quantity\":0}}','2021-05-29 14:16:40','2021-05-29 14:16:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('170','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":12},\"old\":{\"quantity\":0}}','2021-05-29 14:16:40','2021-05-29 14:16:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('171','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-29 14:22:07','2021-05-29 14:22:07','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('172','default','This model has been updated','7','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":17},\"old\":{\"quantity\":1}}','2021-05-29 14:25:57','2021-05-29 14:25:57','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('173','default','This model has been updated','8','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":13},\"old\":{\"quantity\":0}}','2021-05-29 14:26:08','2021-05-29 14:26:08','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('174','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":2},\"old\":{\"quantity\":4}}','2021-05-29 14:34:53','2021-05-29 14:34:53','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('175','default','This model has been updated','1','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":5},\"old\":{\"quantity\":10}}','2021-05-29 14:34:53','2021-05-29 14:34:53','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('176','default','This model has been updated','4','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":1},\"old\":{\"quantity\":2}}','2021-05-29 14:37:23','2021-05-29 14:37:23','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('177','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":7},\"old\":{\"quantity\":12}}','2021-05-29 14:37:23','2021-05-29 14:37:23','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('178','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":12},\"old\":{\"quantity\":7}}','2021-05-29 14:38:00','2021-05-29 14:38:00','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('179','default','user superadmin login date 2021-05-29 19:48:24','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-29T17:48:24.581635Z\"}','2021-05-29 19:48:24','2021-05-29 19:48:24','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('180','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":17},\"old\":{\"quantity\":12}}','2021-05-29 22:25:39','2021-05-29 22:25:39','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('181','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":22},\"old\":{\"quantity\":17}}','2021-05-29 22:26:05','2021-05-29 22:26:05','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('182','default','This model has been updated','1','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":3},\"old\":{\"quantity\":5}}','2021-05-29 22:26:25','2021-05-29 22:26:25','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('183','default','This model has been updated','1','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":8},\"old\":{\"quantity\":3}}','2021-05-29 22:26:54','2021-05-29 22:26:54','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('184','default','This model has been updated','9','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":13},\"old\":{\"quantity\":15}}','2021-05-29 22:27:12','2021-05-29 22:27:12','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('185','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":19},\"old\":{\"quantity\":22}}','2021-05-29 22:27:34','2021-05-29 22:27:34','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('186','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":17},\"old\":{\"quantity\":19}}','2021-05-29 22:30:52','2021-05-29 22:30:52','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('187','default','This model has been updated','9','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":11},\"old\":{\"quantity\":13}}','2021-05-29 22:30:59','2021-05-29 22:30:59','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('188','default','This model has been updated','8','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":8},\"old\":{\"quantity\":13}}','2021-05-29 22:31:06','2021-05-29 22:31:06','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('189','default','This model has been updated','1','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":12},\"old\":{\"quantity\":8}}','2021-05-29 22:31:22','2021-05-29 22:31:22','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('190','default','user superadmin login date 2021-05-30 12:41:59','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-30T10:41:59.966333Z\"}','2021-05-30 12:41:59','2021-05-30 12:41:59','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('191','default','updated','1','App\\Models\\TaxesFees','1','App\\Models\\User','[]','2021-05-30 14:38:16','2021-05-30 14:38:16','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('192','default','created','4','App\\Models\\TaxesFees','1','App\\Models\\User','[]','2021-05-30 14:38:53','2021-05-30 14:38:53','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('193','default','This model has been updated','2','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-05-30 15:20:55','2021-05-30 15:20:55','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('194','default','user superadmin login date 2021-05-30 18:35:01','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-30T16:35:01.948734Z\"}','2021-05-30 18:35:01','2021-05-30 18:35:01','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('195','default','user superadmin login date 2021-05-30 22:34:09','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-30T20:34:09.312816Z\"}','2021-05-30 22:34:09','2021-05-30 22:34:09','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('196','default','user superadmin login date 2021-05-31 12:58:46','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-05-31T10:58:46.198875Z\"}','2021-05-31 12:58:46','2021-05-31 12:58:46','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('197','default','This model has been created','1','App\\Models\\ServiceType','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0642\\u0633\\u0645 1\",\"name_en\":\"section 1\",\"status\":1}}','2021-05-31 15:12:51','2021-05-31 15:12:51','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('198','default','This model has been created','2','App\\Models\\ServiceType','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0642\\u0633\\u0645 2\",\"name_en\":\"section 2\",\"status\":1}}','2021-05-31 15:13:02','2021-05-31 15:13:02','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('199','default','This model has been created','1','App\\Models\\Service','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u062e\\u062f\\u0645\\u0629 1\",\"name_en\":\"service 1\",\"price\":\"1000.00\",\"hours\":10,\"minutes\":5}}','2021-05-31 15:13:19','2021-05-31 15:13:19','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('200','default','This model has been created','1','App\\Models\\MaintenanceDetectionType','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u0646\\u0648\\u0639 1\",\"name_en\":\"type 1\",\"status\":1}}','2021-05-31 16:45:06','2021-05-31 16:45:06','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('201','default','This model has been created','1','App\\Models\\MaintenanceDetection','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"\\u062c\\u0632\\u06211\",\"name_en\":\"section 1\"}}','2021-05-31 16:54:04','2021-05-31 16:54:04','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('202','default','created','1','App\\Models\\Car','1','App\\Models\\User','[]','2021-05-31 18:51:41','2021-05-31 18:51:41','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('203','default','This model has been created','1','App\\Models\\WorkCard','1','App\\Models\\User','{\"attributes\":{\"card_number\":\"1\",\"receive_car_status\":1,\"status\":\"pending\",\"delivery_car_status\":1,\"receive_car_date\":\"2021-05-31\",\"delivery_car_date\":\"2021-05-31\"}}','2021-05-31 18:51:56','2021-05-31 18:51:56','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('204','default','This model has been updated','1','App\\Models\\WorkCard','1','App\\Models\\User','{\"attributes\":{\"status\":\"processing\"},\"old\":{\"status\":\"pending\"}}','2021-05-31 19:11:37','2021-05-31 19:11:37','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('205','default','user superadmin login date 2021-06-01 14:32:31','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-01T12:32:31.305107Z\"}','2021-06-01 14:32:31','2021-06-01 14:32:31','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('206','default','user superadmin logout date 2021-06-01 14:33:05','1','App\\Models\\User','1','App\\Models\\User','{\"logout date\":\"2021-06-01T12:33:05.651616Z\"}','2021-06-01 14:33:05','2021-06-01 14:33:05','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('207','default','user admin1 login date 2021-06-01 14:33:11','2','App\\Models\\User','2','App\\Models\\User','{\"login date\":\"2021-06-01T12:33:11.638158Z\"}','2021-06-01 14:33:11','2021-06-01 14:33:11','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('208','default','user superadmin login date 2021-06-01 22:07:12','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-01T20:07:12.922444Z\"}','2021-06-01 22:07:12','2021-06-01 22:07:12','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('209','default','updated','4','App\\Models\\TaxesFees','1','App\\Models\\User','[]','2021-06-02 01:28:04','2021-06-02 01:28:04','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('210','default','user superadmin login date 2021-06-02 15:43:49','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-02T13:43:49.092718Z\"}','2021-06-02 15:43:49','2021-06-02 15:43:49','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('211','default','user superadmin login date 2021-06-02 21:00:37','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-02T19:00:37.971822Z\"}','2021-06-02 21:00:37','2021-06-02 21:00:37','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('212','default','user superadmin login date 2021-06-03 15:36:47','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-03T13:36:47.024272Z\"}','2021-06-03 15:36:47','2021-06-03 15:36:47','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('213','default','created','5','App\\Models\\TaxesFees','1','App\\Models\\User','[]','2021-06-03 16:19:45','2021-06-03 16:19:45','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('214','default','created','6','App\\Models\\TaxesFees','1','App\\Models\\User','[]','2021-06-03 17:25:56','2021-06-03 17:25:56','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('215','default','updated','6','App\\Models\\TaxesFees','1','App\\Models\\User','[]','2021-06-03 17:26:10','2021-06-03 17:26:10','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('216','default','user superadmin login date 2021-06-03 21:20:56','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-03T19:20:56.274307Z\"}','2021-06-03 21:20:56','2021-06-03 21:20:56','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('217','default','user superadmin login date 2021-06-04 17:33:32','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-04T15:33:32.606520Z\"}','2021-06-04 17:33:32','2021-06-04 17:33:32','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('218','default','This model has been updated','1','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"quantity\":14},\"old\":{\"quantity\":12}}','2021-06-04 18:21:47','2021-06-04 18:21:47','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('219','default','user superadmin login date 2021-06-04 22:10:29','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-04T20:10:29.329287Z\"}','2021-06-04 22:10:29','2021-06-04 22:10:29','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('220','default','user superadmin login date 2021-06-05 17:39:04','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-05T15:39:04.462173Z\"}','2021-06-05 17:39:04','2021-06-05 17:39:04','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('221','default','This model has been created','11','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"John mike\",\"name_en\":\"John\",\"quantity\":0}}','2021-06-05 18:12:39','2021-06-05 18:12:39','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('222','default','user superadmin login date 2021-06-05 21:32:56','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-05T19:32:56.707733Z\"}','2021-06-05 21:32:56','2021-06-05 21:32:56','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('223','default','user superadmin login date 2021-06-06 16:43:40','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-06T14:43:40.295328Z\"}','2021-06-06 16:43:40','2021-06-06 16:43:40','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('224','default','This model has been deleted','11','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":{\"name_ar\":\"John mike\",\"name_en\":\"John\",\"quantity\":0}}','2021-06-06 17:26:03','2021-06-06 17:26:03','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('225','default','user superadmin login date 2021-06-07 02:27:28','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-07T00:27:28.028874Z\"}','2021-06-07 02:27:28','2021-06-07 02:27:28','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('226','default','This model has been created','47','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"invoice_number\":\"7\",\"date\":\"2021-06-07\",\"time\":\"03:28:00\",\"number_of_items\":0,\"total_after_discount\":\"1500.00\",\"is_discount_group_added\":0}}','2021-06-07 03:29:03','2021-06-07 03:29:03','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('227','default','This model has been updated','47','App\\Models\\PurchaseInvoice','1','App\\Models\\User','{\"attributes\":{\"time\":\"03:28:00\",\"number_of_items\":1,\"is_discount_group_added\":0},\"old\":{\"time\":\"03:28\",\"number_of_items\":0,\"is_discount_group_added\":null}}','2021-06-07 03:29:03','2021-06-07 03:29:03','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('228','default','user superadmin login date 2021-06-07 16:58:04','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-07T14:58:04.508325Z\"}','2021-06-07 16:58:04','2021-06-07 16:58:04','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('229','default','user superadmin login date 2021-06-07 17:35:29','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-07T15:35:29.925758Z\"}','2021-06-07 17:35:29','2021-06-07 17:35:29','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('230','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-06-07 20:07:45','2021-06-07 20:07:45','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('231','default','This model has been updated','10','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-06-07 20:07:56','2021-06-07 20:07:56','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('232','default','This model has been updated','1','App\\Models\\Part','1','App\\Models\\User','{\"attributes\":[],\"old\":[]}','2021-06-07 20:14:13','2021-06-07 20:14:13','');

INSERT INTO activity_log (`id`, `log_name`, `description`, `subject_id`, `subject_type`, `causer_id`, `causer_type`, `properties`, `created_at`, `updated_at`, `is_archive`) VALUES 
('233','default','user superadmin login date 2021-06-07 22:20:55','1','App\\Models\\User','1','App\\Models\\User','{\"login date\":\"2021-06-07T20:20:55.209298Z\"}','2021-06-07 22:20:55','2021-06-07 22:20:55','');

INSERT INTO areas (`id`, `name_ar`, `name_en`, `city_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','رمسيس','ramses','1','2021-05-01 20:39:57','2021-05-01 20:39:57','');

INSERT INTO bank_accounts (`id`, `supplier_id`, `bank_name`, `account_name`, `branch`, `account_number`, `iban`, `swift_code`, `created_at`, `updated_at`, `deleted_at`, `customer_id`) VALUES 
('2','2','xccx','xc','','545454','','','2021-05-05 05:01:32','2021-05-08 19:59:32','','');

INSERT INTO bank_accounts (`id`, `supplier_id`, `bank_name`, `account_name`, `branch`, `account_number`, `iban`, `swift_code`, `created_at`, `updated_at`, `deleted_at`, `customer_id`) VALUES 
('7','','بللب','لب','','1212','','','2021-05-07 03:26:33','2021-05-07 03:30:13','','1');

INSERT INTO bank_accounts (`id`, `supplier_id`, `bank_name`, `account_name`, `branch`, `account_number`, `iban`, `swift_code`, `created_at`, `updated_at`, `deleted_at`, `customer_id`) VALUES 
('8','5','dfdf','dffd','','','','','2021-05-07 03:35:21','2021-05-07 03:35:21','','');

INSERT INTO bank_accounts (`id`, `supplier_id`, `bank_name`, `account_name`, `branch`, `account_number`, `iban`, `swift_code`, `created_at`, `updated_at`, `deleted_at`, `customer_id`) VALUES 
('11','8','gfgfgf','','','1212','','','2021-05-12 07:47:49','2021-05-12 07:47:49','','');

INSERT INTO branches (`id`, `name_ar`, `name_en`, `country_id`, `city_id`, `area_id`, `currency_id`, `email`, `address_ar`, `address_en`, `tax_card`, `mailbox_number`, `postal_code`, `phone1`, `phone2`, `fax`, `logo`, `map`, `vat_active`, `vat_percent`, `created_at`, `updated_at`, `deleted_at`, `status`, `shift_is_active`) VALUES 
('1','فرع 1','Branch 1','1','1','1','1','supermsoft@gmail.com','القاهره','cairo','123456','3212121541','154545454','01013046794','01013046794','1515454545','7348b0041de998355339.png','','','','2021-05-01 20:39:57','2021-05-21 18:16:43','','1','1');

INSERT INTO branches (`id`, `name_ar`, `name_en`, `country_id`, `city_id`, `area_id`, `currency_id`, `email`, `address_ar`, `address_en`, `tax_card`, `mailbox_number`, `postal_code`, `phone1`, `phone2`, `fax`, `logo`, `map`, `vat_active`, `vat_percent`, `created_at`, `updated_at`, `deleted_at`, `status`, `shift_is_active`) VALUES 
('2','فرع 2','Branch 2','1','1','1','1','admin2@admin.com','المعادي القاهره','Cairo','123456','4421541','12207444','00201013046794','0100000000','515121541','c3575c7dce1cd8ac9d34.png','','','','2021-05-01 23:06:01','2021-05-16 04:18:46','','1','1');

INSERT INTO branches (`id`, `name_ar`, `name_en`, `country_id`, `city_id`, `area_id`, `currency_id`, `email`, `address_ar`, `address_en`, `tax_card`, `mailbox_number`, `postal_code`, `phone1`, `phone2`, `fax`, `logo`, `map`, `vat_active`, `vat_percent`, `created_at`, `updated_at`, `deleted_at`, `status`, `shift_is_active`) VALUES 
('3','فرع 3','branch 3','1','1','1','1','admin@admin.com','المعادي القاهره','Cairo - Egypt','151541','5152154151','12207','0121211111','011121515511','15151515','3dda3390c6bf883cae0f.png','','','','2021-05-01 23:07:31','2021-05-01 23:07:31','','1','1');

INSERT INTO branches (`id`, `name_ar`, `name_en`, `country_id`, `city_id`, `area_id`, `currency_id`, `email`, `address_ar`, `address_en`, `tax_card`, `mailbox_number`, `postal_code`, `phone1`, `phone2`, `fax`, `logo`, `map`, `vat_active`, `vat_percent`, `created_at`, `updated_at`, `deleted_at`, `status`, `shift_is_active`) VALUES 
('4','fggf','gfgf','1','1','','1','','dssd','dssd','45454','','','','','','','','','','2021-05-07 03:18:28','2021-05-07 03:19:16','2021-05-07 03:19:16','1','');

INSERT INTO branches (`id`, `name_ar`, `name_en`, `country_id`, `city_id`, `area_id`, `currency_id`, `email`, `address_ar`, `address_en`, `tax_card`, `mailbox_number`, `postal_code`, `phone1`, `phone2`, `fax`, `logo`, `map`, `vat_active`, `vat_percent`, `created_at`, `updated_at`, `deleted_at`, `status`, `shift_is_active`) VALUES 
('5','John mike','John mike','1','1','','1','admin@admin.com','القاهره','Cairo','','','12207','07456985655','','','','','','','2021-05-07 03:40:15','2021-05-07 03:40:39','2021-05-07 03:40:39','1','');

INSERT INTO branches (`id`, `name_ar`, `name_en`, `country_id`, `city_id`, `area_id`, `currency_id`, `email`, `address_ar`, `address_en`, `tax_card`, `mailbox_number`, `postal_code`, `phone1`, `phone2`, `fax`, `logo`, `map`, `vat_active`, `vat_percent`, `created_at`, `updated_at`, `deleted_at`, `status`, `shift_is_active`) VALUES 
('6','قبثيب','بييبل','1','1','','1','','ببب','ببب','','','','','','','','','','','2021-05-07 03:40:53','2021-05-07 03:41:16','2021-05-07 03:41:16','1','');

INSERT INTO branches (`id`, `name_ar`, `name_en`, `country_id`, `city_id`, `area_id`, `currency_id`, `email`, `address_ar`, `address_en`, `tax_card`, `mailbox_number`, `postal_code`, `phone1`, `phone2`, `fax`, `logo`, `map`, `vat_active`, `vat_percent`, `created_at`, `updated_at`, `deleted_at`, `status`, `shift_is_active`) VALUES 
('7','hjvhbf','ghjghf454','1','1','','1','','ghjf','fhgjfhg','','','','','','','','','','','2021-05-08 06:12:01','2021-05-08 06:12:26','2021-05-08 06:12:26','1','');

INSERT INTO branches_roles (`id`, `branch_id`, `role_id`, `created_at`, `updated_at`) VALUES 
('1','1','1','','');

INSERT INTO branches_roles (`id`, `branch_id`, `role_id`, `created_at`, `updated_at`) VALUES 
('2','1','2','','');

INSERT INTO branches_roles (`id`, `branch_id`, `role_id`, `created_at`, `updated_at`) VALUES 
('3','2','3','','');

INSERT INTO branches_roles (`id`, `branch_id`, `role_id`, `created_at`, `updated_at`) VALUES 
('4','3','4','','');

INSERT INTO card_invoice_maintenance_detection (`id`, `card_invoice_id`, `maintenance_detection_id`, `maintenance_type_id`, `images`, `notes`, `degree`, `created_at`, `updated_at`) VALUES 
('1','1','1','1','[]','','1','','');

INSERT INTO card_invoice_maintenance_types (`id`, `card_invoice_id`, `maintenance_type_id`, `created_at`, `updated_at`, `discount_type`, `discount`, `sub_total`, `total_after_discount`) VALUES 
('1','1','1','','','amount','0.00','0.00','0.00');

INSERT INTO card_invoices (`id`, `invoice_number`, `work_card_id`, `created_by`, `date`, `time`, `type`, `terms`, `discount_type`, `discount`, `tax`, `sub_total`, `total_after_discount`, `total`, `created_at`, `updated_at`, `deleted_at`, `customer_discount`, `customer_discount_type`, `customer_discount_status`, `points_discount`, `points_rule_id`) VALUES 
('1','1','1','1','2021-05-31','06:51:00','cash','','amount','0.00','0.00','0.00','0.00','0.00','2021-05-31 19:11:37','2021-05-31 19:11:37','','0.00','amount','','0.00','');

INSERT INTO cars (`id`, `type_id`, `model_id`, `company_id`, `plate_number`, `Chassis_number`, `speedometer`, `barcode`, `color`, `image`, `customer_id`, `created_at`, `updated_at`, `deleted_at`, `motor_number`) VALUES 
('1','','','','1111111111','2121','','','#000000','','1','2021-05-31 18:51:41','2021-05-31 18:51:41','','');

INSERT INTO cities (`id`, `name_ar`, `name_en`, `country_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','القاهره','cairo','1','2021-05-01 20:39:57','2021-05-01 20:39:57','');

INSERT INTO concession_executions (`id`, `concession_id`, `date_from`, `date_to`, `status`, `notes`, `created_at`, `updated_at`) VALUES 
('1','1','2021-05-01 00:00:00','2021-05-05 00:00:00','finished','','2021-05-20 15:49:29','2021-05-20 15:55:02');

INSERT INTO concession_executions (`id`, `concession_id`, `date_from`, `date_to`, `status`, `notes`, `created_at`, `updated_at`) VALUES 
('2','2','2021-04-26 00:00:00','2021-05-13 00:00:00','late','','2021-05-20 16:09:55','2021-05-20 16:09:55');

INSERT INTO concession_executions (`id`, `concession_id`, `date_from`, `date_to`, `status`, `notes`, `created_at`, `updated_at`) VALUES 
('3','3','2021-05-01 00:00:00','2021-05-08 00:00:00','pending','','2021-05-20 16:34:41','2021-05-20 16:34:41');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('4','2','4','7','4','1','30000.00','2021-05-19 11:26:45','2021-05-19 11:26:45','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('5','2','5','8','4','2','4000.00','2021-05-19 11:26:45','2021-05-19 11:26:45','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('6','2','7','12','4','1','24000.00','2021-05-19 11:26:45','2021-05-19 11:26:45','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('7','3','4','6','6','5','2500.00','2021-05-19 11:26:58','2021-05-19 11:26:59','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('8','3','5','8','6','5','4000.00','2021-05-19 11:26:58','2021-05-19 11:26:59','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('9','3','7','11','6','5','2000.00','2021-05-19 11:26:58','2021-05-19 11:26:59','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('10','4','4','6','3','4','2500.00','2021-05-19 12:30:19','2021-05-29 14:16:40','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('11','4','7','12','3','1','24000.00','2021-05-19 12:30:19','2021-05-29 14:16:40','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('14','6','4','6','4','1','2500.00','2021-05-20 06:32:03','2021-05-20 06:32:03','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('15','6','5','8','4','1','4000.00','2021-05-20 06:32:03','2021-05-20 06:32:03','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('20','7','4','6','3','2','2500.00','2021-05-20 06:41:28','2021-05-29 14:34:53','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('21','8','4','6','3','1','2000.00','2021-05-20 06:47:10','2021-05-29 14:37:23','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('22','8','5','8','3','1','2000.00','2021-05-20 06:47:10','2021-05-29 14:37:23','','failed','Part quantity less than requested');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('24','2','1','1','1','10','500.00','2021-05-29 14:12:11','2021-05-29 14:12:11','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('25','1','6','8','1','20','500.00','2021-05-29 14:12:22','2021-05-29 14:12:23','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('26','3','9','11','1','15','500.00','2021-05-29 14:16:22','2021-05-29 14:16:22','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('27','4','10','12','2','12','1000.00','2021-05-29 14:16:40','2021-05-29 14:16:40','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('29','6','7','9','1','16','1500.00','2021-05-29 14:25:57','2021-05-29 14:25:57','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('30','5','8','10','3','13','1000.00','2021-05-29 14:26:08','2021-05-29 14:26:08','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('31','7','1','1','1','5','500.00','2021-05-29 14:34:53','2021-05-29 14:34:53','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('32','8','10','12','2','5','1000.00','2021-05-29 14:37:23','2021-05-29 14:37:23','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('34','10','10','12','3','5','1000.00','2021-05-29 14:37:59','2021-05-29 14:38:00','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('35','11','10','12','2','5','1000.00','2021-05-29 22:25:39','2021-05-29 22:25:39','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('36','12','10','12','3','5','1000.00','2021-05-29 22:26:05','2021-05-29 22:26:05','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('37','13','1','1','1','2','500.00','2021-05-29 22:26:25','2021-05-29 22:26:25','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('39','9','1','1','2','5','500.00','2021-05-29 22:26:54','2021-05-29 22:26:54','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('40','14','9','11','1','2','150.00','2021-05-29 22:27:12','2021-05-29 22:27:12','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('41','15','10','12','2','3','500.00','2021-05-29 22:27:34','2021-05-29 22:27:34','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('46','19','1','1','1','4','500.00','2021-05-29 22:30:43','2021-05-29 22:30:43','','failed','Store quantity less than requested ');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('47','18','10','12','2','2','1000.00','2021-05-29 22:30:52','2021-05-29 22:30:52','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('48','17','9','11','1','2','500.00','2021-05-29 22:30:59','2021-05-29 22:30:59','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('49','16','8','10','3','5','500.00','2021-05-29 22:31:06','2021-05-29 22:31:06','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('50','21','1','1','2','4','500.00','2021-05-29 22:31:22','2021-05-29 22:31:22','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('51','22','6','8','1','2','500.00','2021-06-02 22:46:45','2021-06-02 22:46:45','','pending','');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('52','23','1','1','2','2','500.00','2021-06-04 18:21:47','2021-06-04 18:21:47','','success','Quantity saved successfully');

INSERT INTO concession_items (`id`, `concession_id`, `part_id`, `part_price_id`, `store_id`, `quantity`, `price`, `created_at`, `updated_at`, `part_price_segment_id`, `accepted_status`, `log_message`) VALUES 
('53','24','1','1','2','1','500.00','2021-06-07 03:41:27','2021-06-07 03:41:27','','pending','');

INSERT INTO concession_type_items (`id`, `name`, `model`, `type`, `created_at`, `updated_at`) VALUES 
('1','Damaged Stock','DamagedStock','withdrawal','2021-05-02 22:09:45','2021-05-02 22:09:45');

INSERT INTO concession_type_items (`id`, `name`, `model`, `type`, `created_at`, `updated_at`) VALUES 
('2','Positive Settlement','Settlement','add','2021-05-02 22:09:45','2021-05-02 22:09:45');

INSERT INTO concession_type_items (`id`, `name`, `model`, `type`, `created_at`, `updated_at`) VALUES 
('3','Negative Settlement','Settlement','withdrawal','2021-05-02 22:09:45','2021-05-02 22:09:45');

INSERT INTO concession_type_items (`id`, `name`, `model`, `type`, `created_at`, `updated_at`) VALUES 
('4','Opening Balance','OpeningBalance','add','2021-05-03 04:46:11','2021-05-03 04:46:11');

INSERT INTO concession_type_items (`id`, `name`, `model`, `type`, `created_at`, `updated_at`) VALUES 
('8','Add Store Transfer','StoreTransfer','add','2021-05-08 19:52:49','2021-05-08 19:52:49');

INSERT INTO concession_type_items (`id`, `name`, `model`, `type`, `created_at`, `updated_at`) VALUES 
('9','Get Store Transfer','StoreTransfer','withdrawal','2021-05-08 19:52:49','2021-05-08 19:52:49');

INSERT INTO concession_types (`id`, `branch_id`, `name_ar`, `name_en`, `type`, `description`, `concession_type_item_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','1','تسوية موجبة','Positiv','add','','2','2021-05-03 04:47:41','2021-05-03 04:47:41','');

INSERT INTO concession_types (`id`, `branch_id`, `name_ar`, `name_en`, `type`, `description`, `concession_type_item_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','1','رصيد إفتتاحي','opining balance','add','','4','2021-05-03 04:49:27','2021-05-03 06:34:55','2021-05-03 06:34:55');

INSERT INTO concession_types (`id`, `branch_id`, `name_ar`, `name_en`, `type`, `description`, `concession_type_item_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('3','1','مخزون تالف','damaged stock','withdrawal','','1','2021-05-03 04:55:01','2021-05-03 04:55:01','');

INSERT INTO concession_types (`id`, `branch_id`, `name_ar`, `name_en`, `type`, `description`, `concession_type_item_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('4','1','تسوية سالبة','Negative','withdrawal','','3','2021-05-03 04:56:43','2021-05-03 04:56:43','');

INSERT INTO concession_types (`id`, `branch_id`, `name_ar`, `name_en`, `type`, `description`, `concession_type_item_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('5','1','رصيد إفتتاحي','opening balance','add','','4','2021-05-03 06:36:16','2021-05-03 06:36:16','');

INSERT INTO concession_types (`id`, `branch_id`, `name_ar`, `name_en`, `type`, `description`, `concession_type_item_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('6','1','إضافة تحويل مخزني','Add transfer','add','','8','2021-05-08 20:24:40','2021-05-08 20:24:53','');

INSERT INTO concession_types (`id`, `branch_id`, `name_ar`, `name_en`, `type`, `description`, `concession_type_item_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('7','1','صرف تحويل مخزني','Get transfer','withdrawal','','9','2021-05-08 20:25:32','2021-05-08 20:25:32','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('1','1','1','2021-05-29','14:11:44','1','accepted','add','5','14','App\\Models\\OpeningBalance','20','','2021-05-29 14:11:54','2021-05-29 14:15:19','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('2','1','2','2021-05-29','14:11:57','1','accepted','add','5','15','App\\Models\\OpeningBalance','10','','2021-05-29 14:12:11','2021-05-29 14:12:11','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('3','1','3','2021-05-29','14:16:08','1','accepted','add','5','16','App\\Models\\OpeningBalance','15','','2021-05-29 14:16:22','2021-05-29 14:16:22','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('4','1','4','2021-05-29','14:16:24','1','accepted','add','5','17','App\\Models\\OpeningBalance','12','','2021-05-29 14:16:40','2021-05-29 14:16:40','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('5','1','5','2021-05-29','14:25:36','1','accepted','add','5','18','App\\Models\\OpeningBalance','13','','2021-05-29 14:25:45','2021-05-29 14:26:08','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('6','1','6','2021-05-29','14:25:47','1','accepted','add','5','19','App\\Models\\OpeningBalance','16','','2021-05-29 14:25:57','2021-05-29 14:25:57','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('7','1','1','2021-05-29','14:34:25','1','accepted','withdrawal','7','1','App\\Models\\StoreTransfer','5','','2021-05-29 14:34:53','2021-05-29 14:34:53','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('8','1','2','2021-05-29','14:37:12','1','accepted','withdrawal','7','2','App\\Models\\StoreTransfer','5','','2021-05-29 14:37:23','2021-05-29 14:37:23','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('9','1','7','2021-05-29','14:37:27','1','accepted','add','6','1','App\\Models\\StoreTransfer','5','','2021-05-29 14:37:35','2021-05-29 22:26:54','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('10','1','8','2021-05-29','14:37:45','1','accepted','add','6','2','App\\Models\\StoreTransfer','5','','2021-05-29 14:37:59','2021-05-29 14:37:59','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('11','1','9','2021-05-29','22:25:26','1','accepted','add','1','5','App\\Models\\Settlement','5','','2021-05-29 22:25:39','2021-05-29 22:25:39','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('12','1','10','2021-05-29','22:25:53','1','accepted','add','1','6','App\\Models\\Settlement','5','','2021-05-29 22:26:05','2021-05-29 22:26:05','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('13','1','3','2021-05-29','22:26:08','1','accepted','withdrawal','3','10','App\\Models\\DamagedStock','2','','2021-05-29 22:26:25','2021-05-29 22:26:25','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('14','1','4','2021-05-29','22:26:28','1','accepted','withdrawal','3','11','App\\Models\\DamagedStock','2','','2021-05-29 22:26:40','2021-05-29 22:27:12','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('15','1','5','2021-05-29','22:27:22','1','accepted','withdrawal','3','12','App\\Models\\DamagedStock','3','','2021-05-29 22:27:34','2021-05-29 22:27:34','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('16','1','6','2021-05-29','22:27:44','1','accepted','withdrawal','3','13','App\\Models\\DamagedStock','5','','2021-05-29 22:27:53','2021-05-29 22:31:06','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('17','1','7','2021-05-29','22:27:57','1','accepted','withdrawal','4','7','App\\Models\\Settlement','2','','2021-05-29 22:28:05','2021-05-29 22:30:59','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('18','1','8','2021-05-29','22:28:08','1','accepted','withdrawal','4','8','App\\Models\\Settlement','2','','2021-05-29 22:28:23','2021-05-29 22:30:52','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('19','1','9','2021-05-29','22:28:26','1','accepted','withdrawal','7','5','App\\Models\\StoreTransfer','4','','2021-05-29 22:29:19','2021-05-29 22:30:43','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('21','1','11','2021-05-29','22:31:10','1','accepted','add','6','5','App\\Models\\StoreTransfer','4','','2021-05-29 22:31:22','2021-05-29 22:31:22','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('22','1','10','2021-06-02','22:46:32','1','pending','withdrawal','7','6','App\\Models\\StoreTransfer','2','','2021-06-02 22:46:45','2021-06-02 22:46:45','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('23','1','12','2021-06-04','18:21:35','1','accepted','add','1','9','App\\Models\\Settlement','2','','2021-06-04 18:21:47','2021-06-04 18:21:47','','');

INSERT INTO concessions (`id`, `branch_id`, `number`, `date`, `time`, `user_id`, `status`, `type`, `concession_type_id`, `concessionable_id`, `concessionable_type`, `total_quantity`, `description`, `created_at`, `updated_at`, `deleted_at`, `library_path`) VALUES 
('24','1','13','2021-06-07','03:41:08','1','pending','add','1','10','App\\Models\\Settlement','1','','2021-06-07 03:41:27','2021-06-07 03:41:27','','');

INSERT INTO countries (`id`, `name_ar`, `name_en`, `currency_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','مصر','egypt','1','2021-05-01 20:39:57','2021-05-01 20:39:57','');

INSERT INTO currencies (`id`, `name_ar`, `name_en`, `symbol_ar`, `symbol_en`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','جنيه','pound','LE','LE','2021-05-01 20:39:56','2021-05-01 20:39:56','');

INSERT INTO customer_categories (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `sales_discount_type`, `sales_discount`, `services_discount_type`, `services_discount`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','مجموعة 1','group 1','1','1','amount','0','amount','0','','2021-05-05 05:13:35','2021-05-05 05:13:35','');

INSERT INTO customer_categories (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `sales_discount_type`, `sales_discount`, `services_discount_type`, `services_discount`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','مجموعة 2','group 2','1','1','','','','','','2021-05-07 03:23:59','2021-05-07 03:23:59','');

INSERT INTO customer_contacts (`id`, `customer_id`, `phone_1`, `phone_2`, `address`, `created_at`, `updated_at`, `name`) VALUES 
('1','1','15454','454545','','2021-05-05 05:28:56','2021-05-05 05:28:56','fddf');

INSERT INTO customer_contacts (`id`, `customer_id`, `phone_1`, `phone_2`, `address`, `created_at`, `updated_at`, `name`) VALUES 
('2','1','12121','111','','2021-05-07 03:26:33','2021-05-07 03:30:13','لاال');

INSERT INTO customers (`id`, `name_ar`, `name_en`, `address`, `phone1`, `phone2`, `email`, `status`, `notes`, `fax`, `commercial_register`, `cars_number`, `balance_to`, `balance_for`, `tax_card`, `responsible`, `type`, `branch_id`, `customer_category_id`, `country_id`, `city_id`, `created_at`, `updated_at`, `deleted_at`, `area_id`, `password`, `username`, `can_edit`, `provider`, `theme`, `points`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `identity_number`) VALUES 
('1','عميل 11','emp 1','','','','c1@c1.com','1','','','','','','','','','person','1','1','1','1','2021-05-05 05:28:56','2021-05-26 11:13:55','','1','$2y$10$vFDMUaZUxa86sY.pQsHhkeO0B1VarMvaYjavyCFMMaH.hYgBppMhO','c1','1','','dark-blue','','','','','0.00','','145444444');

INSERT INTO damaged_stock_employee_data (`id`, `damaged_stock_id`, `employee_data_id`, `percent`, `amount`, `created_at`, `updated_at`) VALUES 
('3','11','1','100.00','300.00','','');

INSERT INTO damaged_stock_employee_data (`id`, `damaged_stock_id`, `employee_data_id`, `percent`, `amount`, `created_at`, `updated_at`) VALUES 
('4','13','2','100.00','2500.00','','');

INSERT INTO damaged_stock_items (`id`, `damaged_stock_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('13','10','1','1','1','','2','500.00','2021-05-29 22:18:09','2021-05-29 22:18:09');

INSERT INTO damaged_stock_items (`id`, `damaged_stock_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('14','11','9','1','11','','2','150.00','2021-05-29 22:18:54','2021-05-29 22:18:54');

INSERT INTO damaged_stock_items (`id`, `damaged_stock_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('15','12','10','2','12','','3','500.00','2021-05-29 22:19:56','2021-05-29 22:19:56');

INSERT INTO damaged_stock_items (`id`, `damaged_stock_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('16','13','8','3','10','','5','500.00','2021-05-29 22:20:24','2021-05-29 22:20:24');

INSERT INTO damaged_stock_items (`id`, `damaged_stock_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('17','14','1','2','1','','1','500.00','2021-06-07 03:38:29','2021-06-07 03:38:29');

INSERT INTO damaged_stocks (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('10','1','1','1','natural','2021-05-29','22:17:21','1000.00','','2021-05-29 22:18:09','2021-05-29 22:18:09','');

INSERT INTO damaged_stocks (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('11','1','1','2','un_natural','2021-05-29','22:18:11','300.00','','2021-05-29 22:18:54','2021-05-29 22:18:54','');

INSERT INTO damaged_stocks (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('12','1','1','3','natural','2021-05-29','22:19:42','1500.00','','2021-05-29 22:19:56','2021-05-29 22:19:56','');

INSERT INTO damaged_stocks (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('13','1','1','4','un_natural','2021-05-29','22:19:59','2500.00','','2021-05-29 22:20:24','2021-05-29 22:20:24','');

INSERT INTO damaged_stocks (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('14','1','1','5','natural','2021-06-07','03:38:21','500.00','','2021-06-07 03:38:29','2021-06-07 03:38:29','');

INSERT INTO employee_data (`id`, `name_ar`, `name_en`, `Functional_class`, `address`, `phone1`, `phone2`, `id_number`, `end_date_id_number`, `start_date_assign`, `start_date_stay`, `end_date_stay`, `end_date_health`, `number_card_work`, `status`, `cv`, `email`, `notes`, `rating`, `employee_setting_id`, `branch_id`, `country_id`, `city_id`, `area_id`, `national_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','موظف 1','emp 1','','المعادي القاهره','0100000000','01111111111','','','2021-05-01','','','','','1','','','','','1','1','1','1','1','1','2021-05-01 23:38:04','2021-05-01 23:38:52','');

INSERT INTO employee_data (`id`, `name_ar`, `name_en`, `Functional_class`, `address`, `phone1`, `phone2`, `id_number`, `end_date_id_number`, `start_date_assign`, `start_date_stay`, `end_date_stay`, `end_date_health`, `number_card_work`, `status`, `cv`, `email`, `notes`, `rating`, `employee_setting_id`, `branch_id`, `country_id`, `city_id`, `area_id`, `national_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','موظف 2','emp 2','مهندس','القاهره','0100000000','0122222222','','','2021-05-01','','','','','1','','','','','1','1','1','1','1','1','2021-05-01 23:39:47','2021-05-01 23:42:21','');

INSERT INTO employee_settings (`id`, `name_ar`, `name_en`, `time_attend`, `time_leave`, `daily_working_hours`, `annual_vocation_days`, `max_advance`, `amount_account`, `status`, `type_account`, `type_absence`, `type_absence_equal`, `hourly_extra`, `hourly_extra_equal`, `hourly_delay`, `hourly_delay_equal`, `saturday`, `sunday`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `branch_id`, `shift_id`, `created_at`, `updated_at`, `card_work_percent`, `service_status`) VALUES 
('1','إعدادات 1','setting 1','11:37:00','11:37:00','8','50','6000','5000','1','month','discount_day','1','hourly_extra','1','hourly_delay','1','','','','','','','','1','','2021-05-01 23:37:35','2021-05-01 23:37:35','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('1','مدفوعات فاتوره مشتريات','purchase invoice Payments','1','','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('2','مرتجعات فاتوره مبيعات','sales invoice return Payments','1','','2','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('3','مدفوعات سلف','advances Payments','1','','3','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('4','مدفوعات مرتبات','salaries Payments','1','','4','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('5','مدفوعات فاتوره مشتريات','purchase invoice Payments','1','','5','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('6','مرتجعات فاتوره مبيعات','sales invoice return Payments','1','','6','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('7','مدفوعات سلف','advances Payments','1','','7','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('8','مدفوعات مرتبات','salaries Payments','1','','8','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('9','مدفوعات فاتوره مشتريات','purchase invoice Payments','1','','9','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('10','مرتجعات فاتوره مبيعات','sales invoice return Payments','1','','10','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('11','مدفوعات سلف','advances Payments','1','','11','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('12','مدفوعات مرتبات','salaries Payments','1','','12','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('13','مدفوعات فاتوره مشتريات','purchase invoice Payments','1','','13','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('14','مرتجعات فاتوره مبيعات','sales invoice return Payments','1','','14','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('15','مدفوعات سلف','advances Payments','1','','15','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('16','مدفوعات مرتبات','salaries Payments','1','','16','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('17','مدفوعات فاتوره مشتريات','purchase invoice Payments','1','','17','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('18','مرتجعات فاتوره مبيعات','sales invoice return Payments','1','','18','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('19','مدفوعات سلف','advances Payments','1','','19','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('20','مدفوعات مرتبات','salaries Payments','1','','20','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('21','مدفوعات فاتوره مشتريات','purchase invoice Payments','1','','21','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('22','مرتجعات فاتوره مبيعات','sales invoice return Payments','1','','22','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('23','مدفوعات سلف','advances Payments','1','','23','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('24','مدفوعات مرتبات','salaries Payments','1','','24','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('25','مدفوعات فاتوره مشتريات','purchase invoice Payments','1','','25','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('26','مرتجعات فاتوره مبيعات','sales invoice return Payments','1','','26','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('27','مدفوعات سلف','advances Payments','1','','27','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO expenses_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `expense_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('28','مدفوعات مرتبات','salaries Payments','1','','28','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('1','purchase invoice','فاتوره مشتريات','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('2','sales invoice return','مرتجعات فاتوره المبيعات','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('3','advances','السلف','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('4','salaries','مرتبات','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('5','purchase invoice','فاتوره مشتريات','1','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('6','sales invoice return','مرتجعات فاتوره المبيعات','1','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('7','advances','السلف','1','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('8','salaries','مرتبات','1','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('9','purchase invoice','فاتوره مشتريات','1','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('10','sales invoice return','مرتجعات فاتوره المبيعات','1','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('11','advances','السلف','1','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('12','salaries','مرتبات','1','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('13','purchase invoice','فاتوره مشتريات','1','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('14','sales invoice return','مرتجعات فاتوره المبيعات','1','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('15','advances','السلف','1','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('16','salaries','مرتبات','1','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('17','purchase invoice','فاتوره مشتريات','1','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('18','sales invoice return','مرتجعات فاتوره المبيعات','1','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('19','advances','السلف','1','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('20','salaries','مرتبات','1','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('21','purchase invoice','فاتوره مشتريات','1','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('22','sales invoice return','مرتجعات فاتوره المبيعات','1','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('23','advances','السلف','1','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('24','salaries','مرتبات','1','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('25','purchase invoice','فاتوره مشتريات','1','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('26','sales invoice return','مرتجعات فاتوره المبيعات','1','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('27','advances','السلف','1','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO expenses_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('28','salaries','مرتبات','1','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO maintenance_detection_types (`id`, `branch_id`, `name_ar`, `name_en`, `status`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','1','نوع 1','type 1','1','','2021-05-31 16:45:06','2021-05-31 16:45:06','');

INSERT INTO maintenance_detections (`id`, `branch_id`, `maintenance_type_id`, `name_ar`, `name_en`, `status`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','1','1','جزء1','section 1','1','','2021-05-31 16:54:04','2021-05-31 16:54:04','');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('1','2014_10_12_100000_create_password_resets_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('2','2014_5_5_014323_create_users_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('3','2019_12_12_154950_create_modules_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('4','2020_05_04_023620_create_permission_tables','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('5','2020_05_04_195822_create_currencies_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('6','2020_05_05_172238_create_countries_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('7','2020_05_05_172553_create_cities_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('8','2020_05_05_172559_create_areas_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('9','2020_05_05_200742_create_branches_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('10','2020_05_05_213718_add_status_to_branch','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('11','2020_05_06_014308_create_activity_log_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('12','2020_05_06_040702_add_status_to_users_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('13','2020_05_06_184746_create_shifts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('14','2020_05_06_195217_create_supplier_groups_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('15','2020_05_06_204647_add_coumns_to_stores','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('16','2020_05_06_215733_create_suppliers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('17','2020_05_06_221255_add_is_shift_active_to_branches','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('18','2020_05_06_230042_create_taxes_fees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('19','2020_05_07_033439_add_branch_id_to_users_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('20','2020_05_07_174144_create_spare_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('21','2020_05_07_184641_add_status_to_spare_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('22','2020_05_07_201002_create_spare_part_units_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('23','2020_05_07_230045_create_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('24','2020_05_08_045953_create_alternative_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('25','2020_05_08_235713_create_service_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('26','2020_05_09_001157_create_services_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('27','2020_05_09_034509_create_maintenance_detection_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('28','2020_05_09_034738_create_maintenance_detections_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('29','2020_05_09_154601_create_service_packages_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('30','2020_05_11_021024_create_customer_categories_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('31','2020_05_11_175906_create_customers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('32','2020_05_11_175921_create_cars_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('33','2020_05_13_192303_create_revenue_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('34','2020_05_13_193022_create_revenue_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('35','2020_05_13_215832_create_revenue_receipts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('36','2020_05_14_020033_create_shifts_users_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('37','2020_05_15_182305_create_expenses_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('38','2020_05_15_182323_create_expenses_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('39','2020_05_15_211447_create_lockers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('40','2020_05_15_224754_create_lockers_users_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('41','2020_05_16_013057_create_accounts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('42','2020_05_16_021748_create_accounts_users_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('43','2020_05_16_024558_create_locker_transactions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('44','2020_05_16_114849_create_expenses_receipts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('45','2020_05_18_032401_create_locker_transfers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('46','2020_05_18_033809_create_account_transfers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('47','2020_05_18_230100_add_lockers_id_and_bank_i_d_to__expenses_receipt','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('48','2020_05_20_043418_create_branches_roles_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('49','2020_05_20_204611_add_locker_idand_account_id_to_revenues_receipts','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('50','2020_05_22_223443_add_some_columns_to_services_pakcages','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('51','2020_05_30_222449_create_purchase_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('52','2020_05_30_224952_create_purchase_invoice_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('53','2020_05_31_131521_add_purchase_price_to_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('54','2020_05_31_204042_add_purchase_invoice_id_to_expenses_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('55','2020_06_02_210904_create_sales_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('56','2020_06_02_215618_create_sales_invoice_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('57','2020_06_03_181537_create_purchase_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('58','2020_06_03_181555_create_purchase_return_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('59','2020_06_03_213105_add_total_befor_discount-to_invioce_item','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('60','2020_06_03_231748_add_purchase_invoice_return_to_revenue_receipts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('61','2020_06_05_025124_add_sales_invoice_id_to_table_revenue_receipts','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('62','2020_06_11_033122_create_sales_invoice_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('63','2020_06_11_034142_create_sales_invoice_item_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('64','2020_06_12_053444_add_sales_invoice_return_id_to_expenses_receipts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('65','2020_06_18_205631_create_employee_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('66','2020_06_20_114144_create_employee_data_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('67','2020_06_20_202911_create_quotations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('68','2020_06_20_205330_create_quotation_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('69','2020_06_20_205801_create_quotation_type_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('70','2020_06_21_113618_create_employee_attendances_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('71','2020_06_21_195811_create_employee_delays_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('72','2020_06_23_180030_create_employee_reward_discounts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('73','2020_06_24_182614_create_advances_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('74','2020_06_24_215533_add_advance_id_to_expenses_receipts','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('75','2020_06_24_215551_add_advance_id_to_revenue__receipts','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('76','2020_06_27_095127_create_employee_salaries_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('77','2020_06_27_221006_add_employee_salary_id_to_expenses_recipts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('78','2020_07_03_051113_add_number_to_expenses_receipts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('79','2020_07_03_051435_add_number_to_revenue_receipts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('80','2020_07_06_021856_add_customer_discount_to_sales_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('81','2020_07_11_230211_add_account_bank_id_to_advances_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('82','2020_07_12_032405_add_customer_discount_to_sales_invoice_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('83','2020_07_14_195416_add_is_discount_supplier_to_purchase_invoices','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('84','2020_07_15_225053_add_paid_remaining_to_purchase_invoice_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('85','2020_07_20_015532_create_work_cards_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('86','2020_07_20_174838_add_paid_remaining_to_purchase_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('87','2020_07_24_045147_create_card_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('88','2020_07_24_055416_create_card_invoice_maintenance_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('89','2020_07_24_061144_create_card_invoice_maintenance_detection_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('90','2020_07_26_122206_add_is_returned_to_purchase_invoices_tabel','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('91','2020_07_30_152731_add_is_seeder_to_expense_type_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('92','2020_07_30_152744_add_is_seeder_to_expense_item_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('93','2020_07_30_152804_add_is_seeder_to_revenue_type_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('94','2020_07_30_152814_add_is_seeder_to_revenue_item_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('95','2020_08_04_054520_change_balance_column_in_lockers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('96','2020_08_04_060814_change_amount_column_in_locker_transactions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('97','2020_08_04_062144_change_amounte_column_in_locker_transfers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('98','2020_08_04_062413_change_balance_column_in_accounts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('99','2020_08_04_062559_change_amount_column_in_account_transfers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('100','2020_08_06_002825_add_customer_discount_to_card_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('101','2020_08_06_005336_create_card_invoice_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('102','2020_08_06_010019_create_card_invoice_type_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('103','2020_08_07_100834_add_card_work_percent_to_employee_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('104','2020_08_07_100855_create_employee_absences_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('105','2020_08_08_091648_add_card_invoice_id_to_revenue_receipts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('106','2020_08_08_142927_add_pay_type_to_employee_salaries_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('107','2020_08_08_165634_drop__f_k_shift_in_employee_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('108','2020_08_08_222044_add_total_to_card_invoice_maintenance_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('109','2020_08_08_223130_drop_fk_for_employee_data','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('110','2020_08_08_223527_solve_card_work_percent_in_employee_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('111','2020_08_09_013724_add_service_status_to_employee_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('112','2020_08_09_041602_create_card_invoice_type_items_employee_data_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('113','2020_08_10_123905_add_customer_discount_type_to_sales_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('114','2020_08_10_191733_add_account_fields_to_receipts_tables','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('115','2020_08_11_075142_add_soft_delete_to_card_invoice_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('116','2020_08_11_075428_add_soft_delete_to_card_invoice_type_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('117','2020_08_15_063918_add_customer_discount_type_to_sales_invoice_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('118','2020_08_15_095248_create_assets_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('119','2020_08_15_100721_create_follow_ups_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('120','2020_08_15_143619_create_capital_balances_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('121','2020_09_02_172200_add_discount_group_type_to_purchase_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('122','2020_09_06_091017_add_motor_number_to_cars_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('123','2020_09_06_102653_create_companies_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('124','2020_09_06_105317_create_car_models_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('125','2020_09_06_112201_create_car_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('126','2020_09_06_180255_add_payment_type_to_expenses_tabel','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('127','2020_09_06_180328_add_payment_type_to_revenues_tabel','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('128','2020_09_07_172244_add_supplier_discount_to_purchase_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('129','2020_09_09_191150_add_image_to_users_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('130','2020_09_14_155515_drop_foreign_car_id_in_work_cards_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('131','2020_09_19_145338_add_area_id_to_customers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('132','2020_09_19_160147_change_companu_id_in_cars_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('133','2020_09_26_183239_create_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('134','2020_09_30_101212_change_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('135','2020_10_08_160418_add_note_to_work_cards_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('136','2020_10_08_174016_add_filter_setting_to_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('137','2020_10_12_000257_add_tax_to_purchase_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('138','2020_10_13_003410_add_active_purchase_invoice_to_taxes_fees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('139','2020_10_17_161002_add_sell_from_invoice_status_to_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('140','2020_10_18_190018_create_accounts_trees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('141','2020_10_18_190746_create_account_relations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('142','2020_10_24_153422_create_daily_restrictions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('143','2020_10_24_153441_create_daily_restriction_tables_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('144','2020_10_24_200221_add_account_tree_code_to_daily_restriction_tables_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('145','2020_10_29_105743_create_cost_centers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('146','2020_10_30_190510_add_cost_center_columns_to_daily_restriction_tables_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('147','2020_10_31_165535_add_cost_center_to_daily_restriction_tables_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('148','2020_11_01_184241_add_for_account_tree_id_to_daily_restrictions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('149','2020_11_01_215858_create_fiscal_years_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('150','2020_11_01_220246_add_fiscal_year_id_col_to_daily_restrictions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('151','2020_11_04_183449_add_code_to_accounts_trees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('152','2020_11_06_174445_add_subtotal_to_purchase_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('153','2020_11_06_182353_add_subtotal_to_purchase_invoice_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('154','2020_11_08_004350_add_quantity_to_purchase_invoice_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('155','2020_11_08_015902_add_tax_to_purchase_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('156','2020_11_09_202010_add_is_adverse_to_daily_restrictions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('157','2020_11_13_090906_create_adverse_restriction_logs_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('158','2020_11_17_205231_remove_for_account_tree_id_col_from_daily_restrictions','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('159','2020_11_20_151659_add_references_cols_to_daily_restrictions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('160','2020_12_02_150856_change_card_invoice_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('161','2020_12_10_181736_add_branch_id_to_accounting_module','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('162','2020_12_12_155248_create_module_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('163','2020_12_26_024722_create_customer_requests_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('164','2020_12_26_144702_add_password_to_customers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('165','2020_12_28_203544_add_cost_center_to_tables','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('166','2020_12_29_201341_change_account_naute_col_in_account_relations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('167','2020_12_29_201928_add_locker_bank_id_in_account_relations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('168','2021_01_01_155431_add_can_edit_to_table_customers','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('169','2021_01_01_171934_add_mail_to_table_customer_requests','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('170','2021_01_09_032337_add_status_to_quotations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('171','2021_01_09_040211_add_rejected_reason_to_quotations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('172','2021_01_09_041408_add_lat_long_to_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('173','2021_01_12_004130_create_quotation_winch_requests_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('174','2021_01_21_020350_add_provider_to_customer_requests_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('175','2021_01_21_021040_add_provider_to_customers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('176','2021_01_22_031635_create_card_invoice_winch_requests_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('177','2021_01_29_111329_add_theme_to_users_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('178','2021_01_29_111642_add_theme_to_customers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('179','2021_02_01_005703_create_notifications_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('180','2021_02_01_202235_create_store_transfers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('181','2021_02_01_202241_create_store_transfer_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('182','2021_02_02_190633_add_branch_id_to_store_transfers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('183','2021_02_03_174402_add_new_part_id_to_store_transfer_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('184','2021_02_05_150819_create_notification_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('185','2021_02_07_173021_add_custom_type_to_accounts_trees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('186','2021_02_09_140414_create_mail_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('187','2021_02_10_203703_create_customer_reservations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('188','2021_02_12_010115_create_sms_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('189','2021_02_17_225212_add_cost_center_to_employee_salaries_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('190','2021_02_17_230043_add_salary_id_to_advances_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('191','2021_02_26_152949_create_point_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('192','2021_02_26_161113_add_points_to_customers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('193','2021_02_26_194120_create_point_rules_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('194','2021_02_27_034203_create_point_logs_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('195','2021_02_27_165639_add_points_discount_to_sales_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('196','2021_02_28_033146_add_points_discount_to_card_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('197','2021_03_01_034436_add_points_discount_to_sales_invoice_returns_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('198','2021_03_06_195217_change_account_nature_in_account_relations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('199','2021_03_07_195901_create_locker_exchange_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('200','2021_03_07_195924_create_locker_receive_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('201','2021_03_07_195936_create_bank_receive_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('202','2021_03_07_195947_create_bank_exchange_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('203','2021_03_08_032630_add_quotation_terms_to_settings_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('204','2021_03_08_035244_add_location_to_suppliers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('205','2021_03_08_042831_add_location_to_customers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('206','2021_03_09_132521_add_library_path_to_suppliers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('207','2021_03_11_035840_create_supplier_libraries_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('208','2021_03_12_160444_create_customer_libraries_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('209','2021_03_12_160740_add_library_path_to_customers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('210','2021_03_12_181337_create_supplier_contacts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('211','2021_03_12_221413_add_spare_part_id_to_spare_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('212','2021_03_13_133737_create_customer_contacts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('213','2021_03_16_024813_add_name_to_supplier_contacts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('214','2021_03_16_024956_add_name_to_customer_contacts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('215','2021_03_16_143700_create_locker_transfer_pivots_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('216','2021_03_16_143709_create_bank_transfer_pivots_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('217','2021_03_18_133541_add_destination_type_to_locker_exchange_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('218','2021_03_18_133652_add_destination_type_to_bank_exchange_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('219','2021_03_18_133702_add_source_type_to_locker_receive_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('220','2021_03_18_133708_add_source_type_to_bank_receive_permissions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('221','2021_03_20_180419_add_type_to_taxes_fees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('222','2021_03_20_215644_clear_account_relations_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('223','2021_03_20_221144_create_expenses_revenues_related_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('224','2021_03_20_221159_create_lockers_banks_related_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('225','2021_03_20_221212_create_money_permissions_related_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('226','2021_03_20_221229_create_actors_related_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('227','2021_03_22_043653_create_part_libraries_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('228','2021_03_22_043900_add_library_path_to_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('229','2021_03_23_164033_add_branch_id_to_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('230','2021_03_23_180906_create_part_store_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('231','2021_03_23_200750_create_part_spare_part_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('232','2021_03_24_043923_create_part_prices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('233','2021_03_28_044010_add_deleted_at_to_part_prices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('234','2021_03_29_203409_add_quantity_to_part_store_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('235','2021_03_31_151136_create_store_permission_related_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('236','2021_03_31_154651_create_stores_related_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('237','2021_03_31_182501_create_taxes_related_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('238','2021_03_31_200522_create_discount_related_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('239','2021_04_01_192902_create_opening_balances_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('240','2021_04_01_192913_create_opening_balance_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('241','2021_04_03_182241_add_on_parts_to_taxes_fees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('242','2021_04_05_163645_create_part_taxes_fees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('243','2021_04_06_040005_add_part_price_id_to_purchase_invoice_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('244','2021_04_07_034052_add_tax_to_purchase_invoice_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('245','2021_04_07_035541_create_purchase_invoice_items_taxes_fees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('246','2021_04_07_040904_create_purchase_invoice_taxes_fees_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('247','2021_04_09_173321_add_purchase_invoice_id_to_opening_balances_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('248','2021_04_09_173621_add_is_opening_balance_to_purchase_invoices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('249','2021_04_10_173844_add_default_purchase_to_part_prices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('250','2021_04_10_174452_delete_selling_price_from_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('251','2021_04_15_042150_create_concession_type_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('252','2021_04_19_041731_create_concession_types_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('253','2021_04_23_163209_create_concessions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('254','2021_04_24_165915_create_concession_items_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('255','2021_04_25_190254_change_column_types_in_branches_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('256','2021_04_26_002920_change_creator_name_to_employe_id_in_stores_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('257','2021_04_26_204419_create_part_price_segments_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('258','2021_04_27_001808_remove_image_from_spare_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('259','2021_04_27_1430650_empty_stores_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('260','2021_04_28_173327_create_concession_executions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('261','2021_04_28_201534_create_concession_libraries_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('262','2021_04_28_202026_add_library_path_to_concessions_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('263','2021_04_29_160023_drop_discount_type_in_suppliers_groups_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('264','2021_04_29_201827_drop_group_id_from_suppliers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('265','2021_04_29_201945_add_sub_groups_id_to_suppliers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('266','2021_04_30_0112390_add_supplier_types_to_suppliers_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('267','2021_04_30_032302_create_bank_accounts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('268','2021_04_30_082456_add_supplier_barcode_to_part_prices_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('269','2021_04_30_093018_add_taxable_to_parts_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('270','2021_04_30_123636_add_supplier_groups_id_to_suppliers_groups_table','1');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('271','2021_05_01_201959_change_supplier_id_type_in_parts_table','2');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('272','2021_05_01_164015_create_damaged_stocks_table','3');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('273','2021_05_01_221535_create_damaged_stock_items_table','3');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('274','2021_05_02_092044_create_settlements_table','3');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('275','2021_05_02_095700_create_settlement_items_table','3');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('276','2021_05_03_232613_add_customer_id_to_bank_accounts_table','4');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('277','2021_05_05_050334_create_damaged_stock_employee_data_table','5');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('278','2021_05_06_171906_make_email_nullable_in_branches_table','6');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('279','2021_05_06_192357_change_discount_type_to_be_nullable_in_suppliers_groups_table','6');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('280','2021_05_06_202521_make_phone_nullable_in_customer_contacts_table','6');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('281','2021_05_07_235216_add_time_to_store_transfers_table','7');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('282','2021_05_08_001749_add_total_to_store_transfer_items_table','7');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('283','2021_05_09_014558_add_part_price_segment_id_to_concession_items_table','8');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('284','2021_05_10_042615_add_status_to_concession_items_table','9');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('285','2021_05_12_174747_make_email_nullable_in_users_table','10');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('286','2021_05_12_195144_add_is_arhcive_to_activity_log','10');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('287','2021_05_12_054214_create_purchase_requests_table','11');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('288','2021_05_12_055912_create_purchase_request_items_table','11');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('289','2021_05_17_065319_create_purchase_request_executions_table','11');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('290','2021_05_17_071423_create_purchase_request_libraries_table','11');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('291','2021_05_18_051520_create_purchase_request_items_spare_parts_table','12');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('292','2021_05_24_003352_add_identity_number_to_customers_table','13');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('293','2021_05_24_220049_add_user_id_to_opening_balances_table','13');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('294','2021_05_24_061220_create_purchase_quotations_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('295','2021_05_25_011940_create_supply_terms_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('296','2021_05_29_063334_add_purchase_quotation_to_taxes_fees_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('297','2021_05_29_171414_create_purchase_quotation_items_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('298','2021_05_29_183714_create_purchase_quotation_item_taxes_fees_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('299','2021_05_29_185216_create_purchase_quotation_taxes_fees_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('300','2021_05_29_185645_create_purchase_quotation_supply_terms_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('301','2021_05_29_232918_create_purchase_quotation_executions_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('302','2021_05_30_005401_create_purchase_quotation_libraries_table','14');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('303','2021_05_31_072804_add_execution_time_to_taxes_fees_table','15');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('304','2021_06_02_000810_add_part_price_segment_id_to_purchase_quotation_items_table','16');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('305','2021_06_02_024542_create_purchase_quotation_items_spare_parts_table','16');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('306','2021_06_02_200130_create_store_employee_histories_table','17');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('307','2021_06_03_105241_add_additional_payments_to_purchase_quotations_table','17');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('308','2021_06_05_192043_create_supply_orders_table','18');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('309','2021_06_05_192517_create_supply_order_items_table','18');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('310','2021_06_07_051504_create_supply_order_item_taxes_fees_table','19');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('311','2021_06_07_051950_create_supply_order_taxes_fees_table','19');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('312','2021_06_07_052144_create_purchase_quotation_supply_orders_table','19');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('313','2021_06_07_065544_create_supply_order_supply_terms_table','19');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('314','2021_06_07_065739_create_supply_order_executions_table','19');

INSERT INTO migrations (`id`, `migration`, `batch`) VALUES 
('315','2021_06_07_070021_create_supply_order_libraries_table','19');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('1','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('2','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('3','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('4','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('5','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('6','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('7','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('8','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('9','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('10','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('11','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('12','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('13','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('14','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('15','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('16','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('17','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('18','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('19','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('20','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('21','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('22','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('23','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('24','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('25','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('26','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('27','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('28','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('29','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('30','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('31','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('32','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('33','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('34','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('35','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('36','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('37','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('38','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('39','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('40','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('41','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('42','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('43','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('44','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('45','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('46','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('47','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('48','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('49','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('50','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('51','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('52','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('53','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('54','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('55','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('56','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('57','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('58','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('59','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('60','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('61','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('62','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('63','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('64','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('65','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('66','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('67','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('68','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('69','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('70','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('71','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('72','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('73','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('74','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('75','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('76','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('77','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('78','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('79','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('80','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('81','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('82','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('83','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('84','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('85','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('86','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('87','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('88','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('89','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('90','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('91','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('92','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('93','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('94','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('95','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('96','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('97','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('98','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('99','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('100','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('101','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('102','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('103','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('104','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('105','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('106','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('107','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('108','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('109','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('110','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('111','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('112','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('113','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('114','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('115','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('116','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('117','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('118','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('119','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('120','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('121','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('122','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('123','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('124','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('125','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('126','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('127','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('128','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('129','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('130','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('131','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('132','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('133','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('134','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('135','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('136','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('137','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('138','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('139','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('140','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('141','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('142','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('143','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('144','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('145','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('146','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('147','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('148','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('149','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('150','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('151','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('152','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('153','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('154','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('155','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('156','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('157','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('158','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('159','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('160','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('161','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('162','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('163','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('164','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('165','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('166','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('167','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('168','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('169','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('170','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('171','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('172','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('173','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('174','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('175','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('176','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('177','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('178','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('179','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('180','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('181','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('182','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('183','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('184','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('185','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('186','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('187','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('188','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('189','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('190','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('191','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('192','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('193','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('194','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('195','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('196','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('197','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('198','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('199','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('200','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('201','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('202','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('203','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('204','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('205','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('206','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('207','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('208','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('209','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('210','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('211','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('212','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('213','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('214','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('215','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('216','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('217','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('218','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('219','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('220','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('221','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('222','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('223','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('224','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('225','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('226','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('227','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('228','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('229','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('230','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('231','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('232','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('233','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('234','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('235','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('236','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('237','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('238','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('239','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('240','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('241','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('242','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('243','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('244','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('245','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('246','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('247','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('248','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('249','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('250','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('251','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('252','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('253','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('254','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('255','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('256','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('257','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('258','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('259','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('260','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('261','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('262','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('263','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('264','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('265','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('266','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('267','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('268','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('269','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('270','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('271','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('272','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('273','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('274','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('275','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('276','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('277','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('278','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('279','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('280','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('281','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('282','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('283','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('284','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('285','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('286','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('287','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('288','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('289','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('290','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('291','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('292','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('293','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('294','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('295','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('296','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('297','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('298','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('299','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('300','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('301','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('302','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('303','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('304','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('305','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('306','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('307','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('308','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('309','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('310','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('311','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('312','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('313','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('314','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('315','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('316','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('317','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('318','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('319','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('320','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('321','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('322','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('323','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('324','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('325','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('326','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('327','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('328','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('329','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('330','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('331','App\\Models\\User','1');

INSERT INTO model_has_permissions (`permission_id`, `model_type`, `model_id`) VALUES 
('332','App\\Models\\User','1');

INSERT INTO model_has_roles (`role_id`, `model_type`, `model_id`) VALUES 
('1','App\\Models\\User','1');

INSERT INTO model_has_roles (`role_id`, `model_type`, `model_id`) VALUES 
('2','App\\Models\\User','2');

INSERT INTO model_has_roles (`role_id`, `model_type`, `model_id`) VALUES 
('3','App\\Models\\User','3');

INSERT INTO model_has_roles (`role_id`, `model_type`, `model_id`) VALUES 
('4','App\\Models\\User','4');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('1','2','1','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('2','3','1','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('3','4','1','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('4','5','1','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('5','6','2','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('6','7','2','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('7','8','2','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('8','9','2','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('9','10','3','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('10','11','3','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('11','12','4','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('12','13','4','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('13','14','4','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('14','15','4','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('15','16','5','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('16','17','5','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('17','18','5','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('18','19','5','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('19','20','6','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('20','21','6','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('21','22','6','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('22','23','6','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('23','24','7','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('24','25','7','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('25','26','7','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('26','27','7','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('27','28','8','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('28','29','8','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('29','30','8','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('30','31','8','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('31','32','9','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('32','33','9','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('33','34','9','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('34','35','9','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('35','36','10','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('36','37','10','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('37','38','10','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('38','39','10','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('39','40','11','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('40','41','11','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('41','42','11','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('42','43','11','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('43','44','12','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('44','45','12','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('45','46','12','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('46','47','12','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('47','48','13','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('48','49','13','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('49','50','13','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('50','51','13','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('51','52','14','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('52','53','14','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('53','54','14','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('54','55','14','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('55','56','15','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('56','57','15','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('57','58','15','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('58','59','15','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('59','60','16','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('60','61','16','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('61','62','16','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('62','63','16','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('63','64','17','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('64','65','17','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('65','66','17','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('66','67','17','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('67','68','18','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('68','69','18','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('69','70','18','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('70','71','18','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('71','72','19','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('72','73','19','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('73','74','19','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('74','75','19','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('75','76','20','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('76','77','20','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('77','78','20','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('78','79','20','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('79','80','21','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('80','81','21','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('81','82','21','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('82','83','21','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('83','84','22','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('84','85','22','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('85','86','22','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('86','87','22','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('87','88','23','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('88','89','23','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('89','90','23','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('90','91','23','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('91','92','24','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('92','93','24','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('93','94','24','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('94','95','24','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('95','96','25','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('96','97','25','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('97','98','25','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('98','99','25','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('99','100','26','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('100','101','26','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('101','102','26','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('102','103','26','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('103','104','27','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('104','105','27','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('105','106','27','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('106','107','27','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('107','108','28','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('108','109','28','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('109','110','28','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('110','111','28','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('111','112','29','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('112','113','29','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('113','114','29','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('114','115','29','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('115','116','30','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('116','117','30','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('117','118','30','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('118','119','30','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('119','120','31','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('120','121','31','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('121','122','31','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('122','123','31','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('123','124','32','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('124','125','32','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('125','126','32','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('126','127','32','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('127','128','33','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('128','129','33','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('129','130','33','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('130','131','33','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('131','132','34','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('132','133','34','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('133','134','34','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('134','135','34','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('135','136','35','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('136','137','35','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('137','138','35','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('138','139','35','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('139','140','36','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('140','141','36','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('141','142','36','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('142','143','36','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('143','144','37','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('144','145','37','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('145','146','37','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('146','147','37','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('147','148','38','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('148','149','38','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('149','150','38','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('150','151','38','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('151','152','39','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('152','153','39','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('153','154','39','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('154','155','39','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('155','156','40','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('156','157','40','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('157','158','40','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('158','159','40','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('159','160','41','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('160','161','41','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('161','162','42','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('162','163','42','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('163','164','42','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('164','165','42','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('165','166','43','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('166','167','43','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('167','168','43','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('168','169','43','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('169','170','44','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('170','171','44','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('171','172','44','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('172','173','44','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('173','174','45','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('174','175','45','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('175','176','45','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('176','177','45','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('177','178','46','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('178','179','46','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('179','180','46','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('180','181','46','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('181','182','47','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('182','183','47','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('183','184','47','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('184','185','47','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('185','186','48','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('186','187','48','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('187','188','48','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('188','189','48','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('189','190','49','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('190','191','49','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('191','192','49','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('192','193','49','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('193','194','50','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('194','195','50','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('195','196','50','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('196','197','50','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('197','198','51','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('198','199','51','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('199','200','51','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('200','201','51','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('201','202','52','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('202','203','52','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('203','204','52','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('204','205','52','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('205','206','53','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('206','207','53','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('207','208','53','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('208','209','53','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('209','210','54','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('210','211','54','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('211','212','54','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('212','213','54','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('213','214','55','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('214','215','55','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('215','216','56','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('216','217','56','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('217','218','56','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('218','219','56','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('219','220','56','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('220','221','56','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('221','222','57','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('222','223','57','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('223','224','57','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('224','225','58','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('225','226','58','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('226','227','58','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('227','228','58','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('228','229','59','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('229','230','59','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('230','231','59','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('231','232','59','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('232','233','60','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('233','234','60','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('234','235','61','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('235','236','61','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('236','237','62','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('237','238','62','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('238','239','63','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('239','240','64','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('240','241','64','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('241','242','65','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('242','243','65','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('243','244','65','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('244','245','65','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('245','246','66','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('246','247','67','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('247','248','67','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('248','249','67','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('249','250','67','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('250','251','68','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('251','252','69','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('252','253','69','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('253','254','69','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('254','255','69','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('255','256','70','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('256','257','70','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('257','258','70','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('258','259','70','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('259','260','70','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('260','261','70','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('261','262','71','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('262','263','71','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('263','264','71','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('264','265','71','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('265','266','72','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('266','267','72','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('267','268','72','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('268','269','73','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('269','270','73','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('270','271','73','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('271','272','73','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('272','273','74','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('273','274','74','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('274','275','74','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('275','276','75','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('276','277','75','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('277','278','75','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('278','279','76','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('279','280','76','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('280','281','76','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('281','282','77','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('282','283','77','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('283','284','78','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('284','285','78','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('285','286','78','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('286','287','78','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('287','288','67','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('288','289','39','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('289','290','79','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('290','291','79','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('291','292','9','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('292','293','9','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('293','294','9','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('294','295','32','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('295','296','32','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('296','297','32','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('297','298','4','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('298','299','4','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('299','300','4','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('300','301','80','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('301','302','80','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('302','303','80','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('303','304','80','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('304','305','80','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('305','306','80','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('306','307','80','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('307','308','80','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('308','309','81','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('309','310','81','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('310','311','81','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('311','312','81','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('312','313','81','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('313','314','81','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('314','315','81','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('315','316','81','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('316','317','82','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('317','318','82','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('318','319','82','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('319','320','82','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('320','321','82','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('321','322','82','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('322','323','82','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('323','324','82','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('324','325','83','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('325','326','83','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('326','327','83','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('327','328','83','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('328','329','83','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('329','330','83','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('330','331','83','','');

INSERT INTO module_permissions (`id`, `permission_id`, `module_id`, `created_at`, `updated_at`) VALUES 
('331','332','83','','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','users','module_users','2021-05-01 20:39:58','2021-05-01 20:39:58','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','roles','module_roles','2021-05-01 20:39:58','2021-05-01 20:39:58','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('3','logs','module_logs','2021-05-01 20:39:58','2021-05-01 20:39:58','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('4','parts','module_parts','2021-05-01 20:39:58','2021-05-01 20:39:58','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('5','services','module_services','2021-05-01 20:39:59','2021-05-01 20:39:59','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('6','services_types','module_services_types','2021-05-01 20:39:59','2021-05-01 20:39:59','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('7','maintenance_detections','module_maintenance_detections','2021-05-01 20:39:59','2021-05-01 20:39:59','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('8','maintenance_detections_types','module_maintenance_detections_types','2021-05-01 20:39:59','2021-05-01 20:39:59','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('9','suppliers','module_suppliers','2021-05-01 20:39:59','2021-05-01 20:39:59','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('10','supplier_groups','module_supplier_groups','2021-05-01 20:39:59','2021-05-01 20:39:59','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('11','customer_groups','module_customer_groups','2021-05-01 20:39:59','2021-05-01 20:39:59','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('12','lockers','module_lockers','2021-05-01 20:39:59','2021-05-01 20:39:59','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('13','locker_transactions','module_locker_transactions','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('14','locker_transfers','module_locker_transfers','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('15','accounts','module_accounts','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('16','account_transfers','module_account_transfers','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('17','sales_invoices','module_sales_invoices','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('18','sales_invoices_return','module_sales_invoices_return','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('19','quotations','module_quotations','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('20','work_card','module_work_card','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('21','branches','module_branches','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('22','currencies','module_currencies','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('23','countries','module_countries','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('24','cities','module_cities','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('25','areas','module_areas','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('26','taxes','module_taxes','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('27','shifts','module_shifts','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('28','stores','module_stores','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('29','spareParts','module_spareParts','2021-05-01 20:40:00','2021-05-01 20:40:00','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('30','sparePartsUnit','module_sparePartsUnit','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('31','servicePackages','module_servicePackages','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('32','customers','module_customers','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('33','expense_item','module_expense_item','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('34','expense_type','module_expense_type','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('35','expense_receipts','module_expense_receipts','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('36','revenue_item','module_revenue_item','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('37','revenue_type','module_revenue_type','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('38','revenue_receipts','module_revenue_receipts','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('39','purchase_invoices','module_purchase_invoices','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('40','purchase_return_invoices','module_purchase_return_invoices','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('41','db-backup','module_db-backup','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('42','assets','module_assets','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('43','capital-balance','module_capital-balance','2021-05-01 20:40:01','2021-05-01 20:40:01','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('44','employee_settings','module_employee_settings','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('45','employees_data','module_employees_data','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('46','employees_attendance','module_employees_attendance','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('47','employees_delay','module_employees_delay','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('48','employee_reward_discount','module_employee_reward_discount','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('49','employee-absence','module_employee-absence','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('50','advances','module_advances','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('51','employees_salaries','module_employees_salaries','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('52','companies','module_companies','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('53','car_models','module_car_models','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('54','car_types','module_car_types','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('55','setting','module_setting','2021-05-01 20:40:02','2021-05-01 20:40:02','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('56','stores_transfers','module_stores_transfers','2021-05-01 20:40:04','2021-05-01 20:40:04','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('57','trading-account-index','module_trading-account-index','2021-05-01 20:40:04','2021-05-01 20:40:04','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('58','customer_request','module_customer_request','2021-05-01 20:40:04','2021-05-01 20:40:04','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('59','quotation_request','module_quotation_request','2021-05-01 20:40:05','2021-05-01 20:40:05','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('60','notification_setting','module_notification_setting','2021-05-01 20:40:06','2021-05-01 20:40:06','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('61','mail_setting','module_mail_setting','2021-05-01 20:40:08','2021-05-01 20:40:08','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('62','sms_setting','module_sms_setting','2021-05-01 20:40:09','2021-05-01 20:40:09','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('63','maintenance_status','module_maintenance_status','2021-05-01 20:40:10','2021-05-01 20:40:10','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('64','points_setting','module_points_setting','2021-05-01 20:40:11','2021-05-01 20:40:11','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('65','points_rules','module_points_rules','2021-05-01 20:40:12','2021-05-01 20:40:12','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('66','points_log','module_points_log','2021-05-01 20:40:13','2021-05-01 20:40:13','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('67','accounts-tree-index','module_accounts-tree-index','2021-05-01 20:40:15','2021-05-01 20:40:15','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('68','account-guide-index','module_account-guide-index','2021-05-01 20:40:15','2021-05-01 20:40:15','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('69','account-relations','module_account-relations','2021-05-01 20:40:15','2021-05-01 20:40:15','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('70','daily-restrictions','module_daily-restrictions','2021-05-01 20:40:15','2021-05-01 20:40:15','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('71','cost-centers','module_cost-centers','2021-05-01 20:40:15','2021-05-01 20:40:15','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('72','accounting-general-ledger','module_accounting-general-ledger','2021-05-01 20:40:16','2021-05-01 20:40:16','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('73','fiscal-years','module_fiscal-years','2021-05-01 20:40:16','2021-05-01 20:40:16','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('74','trial-balance-index','module_trial-balance-index','2021-05-01 20:40:16','2021-05-01 20:40:16','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('75','balance-sheet-index','module_balance-sheet-index','2021-05-01 20:40:16','2021-05-01 20:40:16','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('76','income-list-index','module_income-list-index','2021-05-01 20:40:16','2021-05-01 20:40:16','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('77','adverse-restrictions','module_adverse-restrictions','2021-05-01 20:40:16','2021-05-01 20:40:16','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('78','opening-balance','module_opening-balance','2021-05-01 20:40:17','2021-05-01 20:40:17','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('79','reservations','module_reservations','2021-05-01 20:40:18','2021-05-01 20:40:18','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('80','bank_exchange_permissions','module_bank_exchange_permissions','2021-05-01 20:40:21','2021-05-01 20:40:21','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('81','bank_receive_permissions','module_bank_receive_permissions','2021-05-01 20:40:21','2021-05-01 20:40:21','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('82','locker_exchange_permissions','module_locker_exchange_permissions','2021-05-01 20:40:22','2021-05-01 20:40:22','');

INSERT INTO modules (`id`, `name`, `display_name`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('83','locker_receive_permissions','module_locker_receive_permissions','2021-05-01 20:40:22','2021-05-01 20:40:22','');

INSERT INTO notification_settings (`id`, `branch_id`, `customer_request`, `quotation_request`, `work_card_status_to_user`, `minimum_parts_request`, `end_work_card_employee`, `end_residence_employee`, `end_medical_insurance_employee`, `quotation_request_status`, `sales_invoice`, `return_sales_invoice`, `work_card`, `work_card_status_to_customer`, `sales_invoice_payments`, `return_sales_invoice_payments`, `work_card_payments`, `follow_up_cars`, `created_at`, `updated_at`) VALUES 
('1','2','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','2021-05-01 23:06:01','2021-05-01 23:06:01');

INSERT INTO notification_settings (`id`, `branch_id`, `customer_request`, `quotation_request`, `work_card_status_to_user`, `minimum_parts_request`, `end_work_card_employee`, `end_residence_employee`, `end_medical_insurance_employee`, `quotation_request_status`, `sales_invoice`, `return_sales_invoice`, `work_card`, `work_card_status_to_customer`, `sales_invoice_payments`, `return_sales_invoice_payments`, `work_card_payments`, `follow_up_cars`, `created_at`, `updated_at`) VALUES 
('2','3','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','2021-05-01 23:07:31','2021-05-01 23:07:31');

INSERT INTO notification_settings (`id`, `branch_id`, `customer_request`, `quotation_request`, `work_card_status_to_user`, `minimum_parts_request`, `end_work_card_employee`, `end_residence_employee`, `end_medical_insurance_employee`, `quotation_request_status`, `sales_invoice`, `return_sales_invoice`, `work_card`, `work_card_status_to_customer`, `sales_invoice_payments`, `return_sales_invoice_payments`, `work_card_payments`, `follow_up_cars`, `created_at`, `updated_at`) VALUES 
('3','1','1','1','1','1','1','1','1','1','1','1','','1','1','1','1','1','2021-05-01 23:27:30','2021-05-01 23:27:30');

INSERT INTO notification_settings (`id`, `branch_id`, `customer_request`, `quotation_request`, `work_card_status_to_user`, `minimum_parts_request`, `end_work_card_employee`, `end_residence_employee`, `end_medical_insurance_employee`, `quotation_request_status`, `sales_invoice`, `return_sales_invoice`, `work_card`, `work_card_status_to_customer`, `sales_invoice_payments`, `return_sales_invoice_payments`, `work_card_payments`, `follow_up_cars`, `created_at`, `updated_at`) VALUES 
('4','4','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','2021-05-07 03:18:29','2021-05-07 03:18:29');

INSERT INTO notification_settings (`id`, `branch_id`, `customer_request`, `quotation_request`, `work_card_status_to_user`, `minimum_parts_request`, `end_work_card_employee`, `end_residence_employee`, `end_medical_insurance_employee`, `quotation_request_status`, `sales_invoice`, `return_sales_invoice`, `work_card`, `work_card_status_to_customer`, `sales_invoice_payments`, `return_sales_invoice_payments`, `work_card_payments`, `follow_up_cars`, `created_at`, `updated_at`) VALUES 
('5','5','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','2021-05-07 03:40:15','2021-05-07 03:40:15');

INSERT INTO notification_settings (`id`, `branch_id`, `customer_request`, `quotation_request`, `work_card_status_to_user`, `minimum_parts_request`, `end_work_card_employee`, `end_residence_employee`, `end_medical_insurance_employee`, `quotation_request_status`, `sales_invoice`, `return_sales_invoice`, `work_card`, `work_card_status_to_customer`, `sales_invoice_payments`, `return_sales_invoice_payments`, `work_card_payments`, `follow_up_cars`, `created_at`, `updated_at`) VALUES 
('6','6','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','2021-05-07 03:40:53','2021-05-07 03:40:53');

INSERT INTO notification_settings (`id`, `branch_id`, `customer_request`, `quotation_request`, `work_card_status_to_user`, `minimum_parts_request`, `end_work_card_employee`, `end_residence_employee`, `end_medical_insurance_employee`, `quotation_request_status`, `sales_invoice`, `return_sales_invoice`, `work_card`, `work_card_status_to_customer`, `sales_invoice_payments`, `return_sales_invoice_payments`, `work_card_payments`, `follow_up_cars`, `created_at`, `updated_at`) VALUES 
('7','7','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','2021-05-08 06:12:01','2021-05-08 06:12:01');

INSERT INTO notifications (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES 
('119b3b29-2e67-47b6-8404-c4575016ce6d','App\\Notifications\\WorkCardStatusNotification','App\\Models\\User','1','{\"work_card_id\":1,\"title\":\"\\u062d\\u0627\\u0644\\u0627\\u062a \\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u0627\\u0644\\u0635\\u064a\\u0627\\u0646\\u0629\",\"message\":\"\\u062a\\u0645 \\u0627\\u0636\\u0627\\u0641\\u0647 \\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u062c\\u062f\\u064a\\u062f \\u0642\\u064a\\u062f \\u0627\\u0644\\u0627\\u0646\\u062a\\u0638\\u0627\\u0631\"}','','2021-05-31 18:51:57','2021-05-31 18:51:57');

INSERT INTO notifications (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES 
('3860c088-a0a9-4f0d-89e6-7b00bbdc4072','App\\Notifications\\WorkCardStatusNotification','App\\Models\\User','1','{\"work_card_id\":1,\"title\":\"\\u062d\\u0627\\u0644\\u0627\\u062a \\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u0627\\u0644\\u0635\\u064a\\u0627\\u0646\\u0629\",\"message\":\"Work Card Status updated to Processing\"}','','2021-05-31 19:11:37','2021-05-31 19:11:37');

INSERT INTO notifications (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES 
('474284f2-0ded-4f2f-ac17-0688ac5b3a57','App\\Notifications\\WorkCardStatusNotification','App\\Models\\User','2','{\"work_card_id\":1,\"title\":\"\\u062d\\u0627\\u0644\\u0627\\u062a \\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u0627\\u0644\\u0635\\u064a\\u0627\\u0646\\u0629\",\"message\":\"\\u062a\\u0645 \\u0627\\u0636\\u0627\\u0641\\u0647 \\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u062c\\u062f\\u064a\\u062f \\u0642\\u064a\\u062f \\u0627\\u0644\\u0627\\u0646\\u062a\\u0638\\u0627\\u0631\"}','','2021-05-31 18:51:57','2021-05-31 18:51:57');

INSERT INTO notifications (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES 
('58013ee2-52dd-494f-b3ba-f2f817eb97ae','App\\Notifications\\WorkCardStatusNotification','App\\Models\\User','2','{\"work_card_id\":1,\"title\":\"\\u062d\\u0627\\u0644\\u0627\\u062a \\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u0627\\u0644\\u0635\\u064a\\u0627\\u0646\\u0629\",\"message\":\"Work Card Status updated to Processing\"}','','2021-05-31 19:11:38','2021-05-31 19:11:38');

INSERT INTO notifications (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES 
('d3e1f0c8-d3cd-4d4b-b419-11154cc8284b','App\\Notifications\\CustomerWorkCardStatusNotification','App\\Models\\Customer','1','{\"work_card_id\":1,\"title\":\"\\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u0627\\u0644\\u0635\\u064a\\u0627\\u0646\\u0629\",\"message\":\"Work Card Status updated to Processing\"}','','2021-05-31 19:11:38','2021-05-31 19:11:38');

INSERT INTO notifications (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES 
('f1bbb66b-10ee-4ed0-b67b-42731a63c00b','App\\Notifications\\CustomerWorkCardStatusNotification','App\\Models\\Customer','1','{\"work_card_id\":1,\"title\":\"\\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u0627\\u0644\\u0635\\u064a\\u0627\\u0646\\u0629\",\"message\":\"\\u062a\\u0645 \\u0627\\u0636\\u0627\\u0641\\u0647 \\u0643\\u0627\\u0631\\u062a \\u0639\\u0645\\u0644 \\u062c\\u062f\\u064a\\u062f \\u0642\\u064a\\u062f \\u0627\\u0644\\u0627\\u0646\\u062a\\u0638\\u0627\\u0631\"}','','2021-05-31 18:51:58','2021-05-31 18:51:58');

INSERT INTO opening_balance_items (`id`, `opening_balance_id`, `part_id`, `part_price_id`, `part_price_price_segment_id`, `quantity`, `default_unit_quantity`, `buy_price`, `store_id`, `created_at`, `updated_at`) VALUES 
('28','14','6','8','','20','1','500','1','2021-05-29 14:09:34','2021-05-29 14:09:34');

INSERT INTO opening_balance_items (`id`, `opening_balance_id`, `part_id`, `part_price_id`, `part_price_price_segment_id`, `quantity`, `default_unit_quantity`, `buy_price`, `store_id`, `created_at`, `updated_at`) VALUES 
('29','15','1','1','','10','1','500','1','2021-05-29 14:09:51','2021-05-29 14:09:51');

INSERT INTO opening_balance_items (`id`, `opening_balance_id`, `part_id`, `part_price_id`, `part_price_price_segment_id`, `quantity`, `default_unit_quantity`, `buy_price`, `store_id`, `created_at`, `updated_at`) VALUES 
('30','16','9','11','','15','1','500','1','2021-05-29 14:10:07','2021-05-29 14:10:07');

INSERT INTO opening_balance_items (`id`, `opening_balance_id`, `part_id`, `part_price_id`, `part_price_price_segment_id`, `quantity`, `default_unit_quantity`, `buy_price`, `store_id`, `created_at`, `updated_at`) VALUES 
('31','17','10','12','','12','1','1000','2','2021-05-29 14:10:32','2021-05-29 14:10:32');

INSERT INTO opening_balance_items (`id`, `opening_balance_id`, `part_id`, `part_price_id`, `part_price_price_segment_id`, `quantity`, `default_unit_quantity`, `buy_price`, `store_id`, `created_at`, `updated_at`) VALUES 
('32','18','8','10','','13','1','1000','3','2021-05-29 14:10:49','2021-05-29 14:10:49');

INSERT INTO opening_balance_items (`id`, `opening_balance_id`, `part_id`, `part_price_id`, `part_price_price_segment_id`, `quantity`, `default_unit_quantity`, `buy_price`, `store_id`, `created_at`, `updated_at`) VALUES 
('33','19','7','9','','16','1','1500','1','2021-05-29 14:11:09','2021-05-29 14:11:09');

INSERT INTO opening_balance_items (`id`, `opening_balance_id`, `part_id`, `part_price_id`, `part_price_price_segment_id`, `quantity`, `default_unit_quantity`, `buy_price`, `store_id`, `created_at`, `updated_at`) VALUES 
('34','20','1','1','','3','1','500','1','2021-06-07 03:29:03','2021-06-07 03:29:03');

INSERT INTO opening_balances (`id`, `branch_id`, `serial_number`, `operation_date`, `operation_time`, `notes`, `total_money`, `created_at`, `updated_at`, `purchase_invoice_id`, `user_id`) VALUES 
('14','1','1','2021-05-29','14:09','','10000','2021-05-29 14:09:34','2021-05-29 14:09:34','41','1');

INSERT INTO opening_balances (`id`, `branch_id`, `serial_number`, `operation_date`, `operation_time`, `notes`, `total_money`, `created_at`, `updated_at`, `purchase_invoice_id`, `user_id`) VALUES 
('15','1','2','2021-05-29','14:09','','5000','2021-05-29 14:09:51','2021-05-29 14:09:51','42','1');

INSERT INTO opening_balances (`id`, `branch_id`, `serial_number`, `operation_date`, `operation_time`, `notes`, `total_money`, `created_at`, `updated_at`, `purchase_invoice_id`, `user_id`) VALUES 
('16','1','3','2021-05-29','14:09','','7500','2021-05-29 14:10:07','2021-05-29 14:10:07','43','1');

INSERT INTO opening_balances (`id`, `branch_id`, `serial_number`, `operation_date`, `operation_time`, `notes`, `total_money`, `created_at`, `updated_at`, `purchase_invoice_id`, `user_id`) VALUES 
('17','1','4','2021-05-29','14:10','','12000','2021-05-29 14:10:32','2021-05-29 14:10:32','44','1');

INSERT INTO opening_balances (`id`, `branch_id`, `serial_number`, `operation_date`, `operation_time`, `notes`, `total_money`, `created_at`, `updated_at`, `purchase_invoice_id`, `user_id`) VALUES 
('18','1','5','2021-05-29','14:10','','13000','2021-05-29 14:10:49','2021-05-29 14:10:49','45','1');

INSERT INTO opening_balances (`id`, `branch_id`, `serial_number`, `operation_date`, `operation_time`, `notes`, `total_money`, `created_at`, `updated_at`, `purchase_invoice_id`, `user_id`) VALUES 
('19','1','6','2021-05-29','14:10','','24000','2021-05-29 14:11:09','2021-05-29 14:11:09','46','1');

INSERT INTO opening_balances (`id`, `branch_id`, `serial_number`, `operation_date`, `operation_time`, `notes`, `total_money`, `created_at`, `updated_at`, `purchase_invoice_id`, `user_id`) VALUES 
('20','1','7','2021-06-07','03:28','','1500','2021-06-07 03:29:03','2021-06-07 03:29:03','47','1');

INSERT INTO part_libraries (`id`, `part_id`, `file_name`, `name`, `extension`, `created_at`, `updated_at`) VALUES 
('1','10','5c32c1e22c23642a67cc.jpg','2.jpg','jpg','2021-05-29 14:22:07','2021-05-29 14:22:07');

INSERT INTO part_libraries (`id`, `part_id`, `file_name`, `name`, `extension`, `created_at`, `updated_at`) VALUES 
('2','10','1da8468782dfbf11cf1c.jpg','اهميه-ادارة-المؤسسة-التعليمية.jpg','jpg','2021-05-29 14:22:16','2021-05-29 14:22:16');

INSERT INTO part_price_segments (`id`, `part_price_id`, `name`, `purchase_price`, `sales_price`, `maintenance_price`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','1','شريحة 1','600.00','1200.00','1200.00','2021-05-28 21:40:08','2021-05-28 21:40:08','');

INSERT INTO part_price_segments (`id`, `part_price_id`, `name`, `purchase_price`, `sales_price`, `maintenance_price`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','1','شريحة 2','800.00','1500.00','2000.00','2021-05-28 21:40:08','2021-05-28 21:40:08','');

INSERT INTO part_price_segments (`id`, `part_price_id`, `name`, `purchase_price`, `sales_price`, `maintenance_price`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('3','1','شريحة 3','1000.00','2000.00','3000.00','2021-05-28 21:40:08','2021-05-28 21:40:08','');

INSERT INTO part_price_segments (`id`, `part_price_id`, `name`, `purchase_price`, `sales_price`, `maintenance_price`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('4','3','شريحة 1','200.00','300.00','300.00','2021-05-28 22:17:29','2021-05-28 22:17:29','');

INSERT INTO part_price_segments (`id`, `part_price_id`, `name`, `purchase_price`, `sales_price`, `maintenance_price`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('5','4','شريحة 3','1000.00','1500.00','2000.00','2021-05-28 22:49:56','2021-05-28 22:49:56','');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('1','1','7','021215nhjf','1000.00','500.00','800.00','850.00','800.00','10','5','10.00','15.00','1','0.00','0.00','2021-05-28 21:40:08','2021-05-28 21:40:08','','','','','fhfgh44444','500.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('2','1','8','','10000.00','5000.00','8000.00','8500.00','8000.00','','','0.00','0.00','10','0.00','0.00','2021-05-28 21:40:08','2021-05-28 21:40:08','','','','','','0.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('3','2','7','ff1212100','200.00','100.00','150.00','200.00','150.00','10','10','15.00','25.00','1','0.00','0.00','2021-05-28 22:17:29','2021-05-28 22:17:29','','','','','125454bb','100.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('4','2','8','1545fd4','2000.00','1000.00','1500.00','2000.00','1500.00','','','0.00','0.00','10','0.00','0.00','2021-05-28 22:49:56','2021-05-28 22:49:56','','','','','fd4545','0.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('5','3','6','df15df1','300.00','100.00','250.00','250.00','250.00','10','5','10.00','20.00','1','0.00','0.00','2021-05-28 22:51:57','2021-05-28 22:51:57','','','','','fff122111','150.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('6','4','6','sd4ds4','2000.00','1000.00','1500.00','1500.00','1500.00','10','5','10.00','20.00','1','0.00','0.00','2021-05-28 22:57:26','2021-05-28 22:57:26','','','','','ds4ds444','500.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('7','5','6','sd44444','1200.00','600.00','1000.00','1000.00','1000.00','1000','10','10.00','20.00','1','0.00','0.00','2021-05-28 23:19:13','2021-05-28 23:19:13','','','','','sd55555','500.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('8','6','6','ds1sd111','1000.00','500.00','600.00','600.00','600.00','60','10','10.00','20.00','1','0.00','0.00','2021-05-28 23:20:21','2021-05-28 23:20:21','','','','','ss112saSA','200.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('9','7','5','sd1xz1111','2000.00','1500.00','1600.00','1600.00','1600.00','10','5','10.00','20.00','1','0.00','0.00','2021-05-28 23:23:06','2021-05-28 23:23:06','','','','','ss11sa2a','200.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('10','8','5','dssd4444','2000.00','1000.00','1500.00','1500.00','1500.00','10','5','10.00','20.00','1','0.00','0.00','2021-05-29 00:19:31','2021-05-29 00:19:31','','','','','5ds5s5s5','500.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('11','9','5','1sd1sd11','1000.00','500.00','800.00','800.00','800.00','80','10','10.00','200.00','1','0.00','0.00','2021-05-29 00:20:21','2021-05-29 00:20:21','','','','','d1sd2s22','150.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('12','10','5','45as4s4','1200.00','1000.00','1100.00','1100.00','1100.00','10','5','10.00','20.00','1','0.00','0.00','2021-05-29 00:21:23','2021-05-29 00:21:23','','','','','s55s2s22','500.00');

INSERT INTO part_prices (`id`, `part_id`, `unit_id`, `barcode`, `selling_price`, `purchase_price`, `less_selling_price`, `service_selling_price`, `less_service_selling_price`, `maximum_sale_amount`, `minimum_for_order`, `biggest_percent_discount`, `biggest_amount_discount`, `quantity`, `last_selling_price`, `last_purchase_price`, `created_at`, `updated_at`, `deleted_at`, `default_purchase`, `default_sales`, `default_maintenance`, `supplier_barcode`, `damage_price`) VALUES 
('13','11','4','','100.00','50.00','0.00','0.00','0.00','','','0.00','0.00','1','0.00','0.00','2021-06-05 18:12:39','2021-06-06 17:26:03','2021-06-06 17:26:03','','','','','0.00');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('1','1','13');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('2','1','19');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('3','2','13');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('4','2','19');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('5','3','13');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('6','3','20');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('7','4','13');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('8','4','20');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('9','5','13');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('10','5','21');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('11','6','13');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('12','6','21');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('13','7','14');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('14','7','25');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('15','8','14');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('16','8','26');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('17','9','14');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('18','9','28');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('19','10','14');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('20','10','29');

INSERT INTO part_spare_part (`id`, `part_id`, `spare_part_type_id`) VALUES 
('21','11','13');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('1','1','2','11','2021-05-28 21:40:08','2021-06-04 18:21:47');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('2','2','3','','2021-05-28 22:17:29','2021-05-28 22:17:29');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('3','5','2','','2021-05-28 23:19:13','2021-05-28 23:19:13');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('4','6','2','','2021-05-28 23:20:21','2021-05-28 23:20:21');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('5','7','2','','2021-05-28 23:23:06','2021-05-28 23:23:06');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('6','4','4','','2021-05-29 14:12:11','2021-05-29 14:12:11');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('7','5','4','','2021-05-29 14:12:11','2021-05-29 14:12:11');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('8','7','4','','2021-05-29 14:12:11','2021-05-29 14:12:11');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('9','1','1','3','2021-05-29 14:12:11','2021-05-29 22:26:25');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('10','6','1','20','2021-05-29 14:12:22','2021-05-29 14:12:23');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('11','4','6','','2021-05-29 14:16:22','2021-05-29 14:16:22');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('12','5','6','','2021-05-29 14:16:22','2021-05-29 14:16:22');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('13','7','6','','2021-05-29 14:16:22','2021-05-29 14:16:22');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('14','9','1','11','2021-05-29 14:16:22','2021-05-29 22:30:59');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('15','4','3','1','2021-05-29 14:16:40','2021-05-29 14:37:23');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('16','7','3','1','2021-05-29 14:16:40','2021-05-29 14:16:40');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('17','10','2','7','2021-05-29 14:16:40','2021-05-29 22:30:52');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('18','4','4','','2021-05-29 14:25:57','2021-05-29 14:25:57');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('19','5','4','','2021-05-29 14:25:57','2021-05-29 14:25:57');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('20','7','1','16','2021-05-29 14:25:57','2021-05-29 14:25:57');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('21','8','3','8','2021-05-29 14:26:08','2021-05-29 22:31:06');

INSERT INTO part_store (`id`, `part_id`, `store_id`, `quantity`, `created_at`, `updated_at`) VALUES 
('22','10','3','10','2021-05-29 14:38:00','2021-05-29 22:26:05');

INSERT INTO part_taxes_fees (`id`, `part_id`, `taxes_fees_id`) VALUES 
('1','2','2');

INSERT INTO part_taxes_fees (`id`, `part_id`, `taxes_fees_id`) VALUES 
('2','2','3');

INSERT INTO part_taxes_fees (`id`, `part_id`, `taxes_fees_id`) VALUES 
('3','10','2');

INSERT INTO part_taxes_fees (`id`, `part_id`, `taxes_fees_id`) VALUES 
('4','1','3');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('1','Swedex wire 2 mm','سلك سويديكس 2مم','7','','48f4751224fdf214de08.png','14','1','2021-05-28 21:40:08','2021-06-07 20:14:13','','','1','','','1','1','a:1:{i:0;s:1:\"2\";}');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('2','Swedex wire 16 mm','سلك سويديكس 16مم','7','','','','1','2021-05-28 22:17:29','2021-05-30 15:20:55','','','1','','','1','1','a:1:{i:0;s:1:\"2\";}');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('3','Circuit breaker 16 mono amp chino','قاطع 16احادي امبير بتشينو','6','','f6b9a3983ed462a8f086.png','','1','2021-05-28 22:51:57','2021-05-28 22:51:57','','','1','','','','','');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('4','Bicino 32 amp mono circuit breaker','قاطع احادي 32 امبير بتشينو','6','','5924834e9a4ad8aa3c78.png','1','1','2021-05-28 22:57:26','2021-05-29 14:37:23','','','1','','','','','');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('5','Single circuit breaker 32 amp Venus','قاطع احادي 32 امبير فينوس','6','','20426fe1cc29aa685b09.png','','1','2021-05-28 23:19:13','2021-05-28 23:19:13','','','1','','','','','');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('6','16-ampere amperage circuit breaker of Venus','قاطع 16احادي امبير فينوس','6','','','20','1','2021-05-28 23:20:21','2021-05-29 14:12:23','','','1','','','','','');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('7','Basin Innova Egypt','حوض اينوفا مصر','5','','','17','1','2021-05-28 23:23:06','2021-05-29 14:25:57','','','1','','','','','');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('8','New center bathtub','بانيو وسط جديد','5','','','8','1','2021-05-29 00:19:31','2021-05-29 22:31:06','','','1','','','','','');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('9','Shower mixer','خلاط دش','5','','','11','1','2021-05-29 00:20:21','2021-05-29 22:30:59','','','1','','','','','');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('10','Kitchen mixer','خلاط مطبخ','5','','','17','1','2021-05-29 00:21:23','2021-06-07 20:07:56','','kitchen-mixer-10','1','','','1','1','');

INSERT INTO parts (`id`, `name_en`, `name_ar`, `spare_part_unit_id`, `description`, `img`, `quantity`, `status`, `created_at`, `updated_at`, `deleted_at`, `library_path`, `branch_id`, `is_service`, `part_in_store`, `reviewable`, `taxable`, `suppliers_ids`) VALUES 
('11','John','John mike','4','','','','1','2021-06-05 18:12:39','2021-06-06 17:26:03','2021-06-06 17:26:03','','1','','','','','');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('1','login-admin','web','2021-05-01 20:39:57','2021-05-01 20:39:57');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('2','view_users','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('3','create_users','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('4','update_users','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('5','delete_users','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('6','view_roles','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('7','create_roles','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('8','update_roles','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('9','delete_roles','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('10','view_logs','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('11','delete_logs','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('12','view_parts','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('13','create_parts','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('14','update_parts','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('15','delete_parts','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('16','view_services','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('17','create_services','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('18','update_services','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('19','delete_services','web','2021-05-01 20:39:58','2021-05-01 20:39:58');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('20','view_services_types','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('21','create_services_types','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('22','update_services_types','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('23','delete_services_types','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('24','view_maintenance_detections','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('25','create_maintenance_detections','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('26','update_maintenance_detections','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('27','delete_maintenance_detections','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('28','view_maintenance_detections_types','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('29','create_maintenance_detections_types','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('30','update_maintenance_detections_types','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('31','delete_maintenance_detections_types','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('32','view_suppliers','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('33','create_suppliers','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('34','update_suppliers','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('35','delete_suppliers','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('36','view_supplier_groups','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('37','create_supplier_groups','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('38','update_supplier_groups','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('39','delete_supplier_groups','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('40','view_customer_groups','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('41','create_customer_groups','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('42','update_customer_groups','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('43','delete_customer_groups','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('44','view_lockers','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('45','create_lockers','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('46','update_lockers','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('47','delete_lockers','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('48','view_locker_transactions','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('49','create_locker_transactions','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('50','update_locker_transactions','web','2021-05-01 20:39:59','2021-05-01 20:39:59');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('51','delete_locker_transactions','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('52','view_locker_transfers','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('53','create_locker_transfers','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('54','update_locker_transfers','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('55','delete_locker_transfers','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('56','view_accounts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('57','create_accounts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('58','update_accounts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('59','delete_accounts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('60','view_account_transfers','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('61','create_account_transfers','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('62','update_account_transfers','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('63','delete_account_transfers','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('64','view_sales_invoices','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('65','create_sales_invoices','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('66','update_sales_invoices','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('67','delete_sales_invoices','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('68','view_sales_invoices_return','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('69','create_sales_invoices_return','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('70','update_sales_invoices_return','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('71','delete_sales_invoices_return','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('72','view_quotations','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('73','create_quotations','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('74','update_quotations','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('75','delete_quotations','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('76','view_work_card','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('77','create_work_card','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('78','update_work_card','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('79','delete_work_card','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('80','view_branches','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('81','create_branches','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('82','update_branches','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('83','delete_branches','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('84','view_currencies','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('85','create_currencies','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('86','update_currencies','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('87','delete_currencies','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('88','view_countries','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('89','create_countries','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('90','update_countries','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('91','delete_countries','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('92','view_cities','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('93','create_cities','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('94','update_cities','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('95','delete_cities','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('96','view_areas','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('97','create_areas','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('98','update_areas','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('99','delete_areas','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('100','view_taxes','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('101','create_taxes','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('102','update_taxes','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('103','delete_taxes','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('104','view_shifts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('105','create_shifts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('106','update_shifts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('107','delete_shifts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('108','view_stores','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('109','create_stores','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('110','update_stores','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('111','delete_stores','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('112','view_spareParts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('113','create_spareParts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('114','update_spareParts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('115','delete_spareParts','web','2021-05-01 20:40:00','2021-05-01 20:40:00');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('116','view_sparePartsUnit','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('117','create_sparePartsUnit','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('118','update_sparePartsUnit','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('119','delete_sparePartsUnit','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('120','view_servicePackages','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('121','create_servicePackages','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('122','update_servicePackages','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('123','delete_servicePackages','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('124','view_customers','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('125','create_customers','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('126','update_customers','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('127','delete_customers','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('128','view_expense_item','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('129','create_expense_item','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('130','update_expense_item','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('131','delete_expense_item','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('132','view_expense_type','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('133','create_expense_type','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('134','update_expense_type','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('135','delete_expense_type','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('136','view_expense_receipts','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('137','create_expense_receipts','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('138','update_expense_receipts','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('139','delete_expense_receipts','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('140','view_revenue_item','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('141','create_revenue_item','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('142','update_revenue_item','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('143','delete_revenue_item','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('144','view_revenue_type','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('145','create_revenue_type','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('146','update_revenue_type','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('147','delete_revenue_type','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('148','view_revenue_receipts','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('149','create_revenue_receipts','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('150','update_revenue_receipts','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('151','delete_revenue_receipts','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('152','view_purchase_invoices','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('153','create_purchase_invoices','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('154','update_purchase_invoices','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('155','delete_purchase_invoices','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('156','view_purchase_return_invoices','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('157','create_purchase_return_invoices','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('158','update_purchase_return_invoices','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('159','delete_purchase_return_invoices','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('160','view_db-backup','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('161','create_db-backup','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('162','view_assets','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('163','create_assets','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('164','update_assets','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('165','delete_assets','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('166','view_capital-balance','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('167','create_capital-balance','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('168','update_capital-balance','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('169','delete_capital-balance','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('170','view_employee_settings','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('171','create_employee_settings','web','2021-05-01 20:40:01','2021-05-01 20:40:01');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('172','update_employee_settings','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('173','delete_employee_settings','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('174','view_employees_data','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('175','create_employees_data','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('176','update_employees_data','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('177','delete_employees_data','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('178','view_employees_attendance','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('179','create_employees_attendance','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('180','update_employees_attendance','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('181','delete_employees_attendance','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('182','view_employees_delay','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('183','create_employees_delay','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('184','update_employees_delay','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('185','delete_employees_delay','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('186','view_employee_reward_discount','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('187','create_employee_reward_discount','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('188','update_employee_reward_discount','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('189','delete_employee_reward_discount','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('190','view_employee-absence','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('191','create_employee-absence','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('192','update_employee-absence','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('193','delete_employee-absence','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('194','view_advances','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('195','create_advances','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('196','update_advances','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('197','delete_advances','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('198','view_employees_salaries','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('199','create_employees_salaries','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('200','update_employees_salaries','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('201','delete_employees_salaries','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('202','view_companies','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('203','create_companies','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('204','update_companies','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('205','delete_companies','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('206','view_car_models','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('207','create_car_models','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('208','update_car_models','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('209','delete_car_models','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('210','view_car_types','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('211','create_car_types','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('212','update_car_types','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('213','delete_car_types','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('214','view_setting','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('215','update_setting','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('216','view_stores_transfers','web','2021-05-01 20:40:03','2021-05-01 20:40:03');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('217','create_stores_transfers','web','2021-05-01 20:40:03','2021-05-01 20:40:03');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('218','edit_stores_transfers','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('219','delete_stores_transfers','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('220','print_stores_transfers','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('221','export_stores_transfers','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('222','view_trading-account-index','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('223','print_trading-account-index','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('224','export_trading-account-index','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('225','view_customer_request','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('226','accept_customer_request','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('227','reject_customer_request','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('228','delete_customer_request','web','2021-05-01 20:40:04','2021-05-01 20:40:04');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('229','view_quotation_request','web','2021-05-01 20:40:05','2021-05-01 20:40:05');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('230','accept_quotation_request','web','2021-05-01 20:40:05','2021-05-01 20:40:05');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('231','reject_quotation_request','web','2021-05-01 20:40:05','2021-05-01 20:40:05');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('232','delete_quotation_request','web','2021-05-01 20:40:05','2021-05-01 20:40:05');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('233','view_notification_setting','web','2021-05-01 20:40:06','2021-05-01 20:40:06');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('234','update_notification_setting','web','2021-05-01 20:40:06','2021-05-01 20:40:06');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('235','view_mail_setting','web','2021-05-01 20:40:08','2021-05-01 20:40:08');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('236','update_mail_setting','web','2021-05-01 20:40:08','2021-05-01 20:40:08');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('237','view_sms_setting','web','2021-05-01 20:40:09','2021-05-01 20:40:09');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('238','update_sms_setting','web','2021-05-01 20:40:09','2021-05-01 20:40:09');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('239','view_maintenance_status','web','2021-05-01 20:40:10','2021-05-01 20:40:10');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('240','view_points_setting','web','2021-05-01 20:40:11','2021-05-01 20:40:11');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('241','update_points_setting','web','2021-05-01 20:40:11','2021-05-01 20:40:11');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('242','view_points_rules','web','2021-05-01 20:40:12','2021-05-01 20:40:12');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('243','create_points_rules','web','2021-05-01 20:40:12','2021-05-01 20:40:12');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('244','update_points_rules','web','2021-05-01 20:40:12','2021-05-01 20:40:12');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('245','delete_points_rules','web','2021-05-01 20:40:12','2021-05-01 20:40:12');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('246','view_points_log','web','2021-05-01 20:40:13','2021-05-01 20:40:13');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('247','view_accounts-tree-index','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('248','create_accounts-tree-index','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('249','edit_accounts-tree-index','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('250','delete_accounts-tree-index','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('251','view_account-guide-index','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('252','view_account-relations','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('253','create_account-relations','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('254','edit_account-relations','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('255','delete_account-relations','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('256','view_daily-restrictions','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('257','create_daily-restrictions','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('258','edit_daily-restrictions','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('259','delete_daily-restrictions','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('260','print_daily-restrictions','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('261','export_daily-restrictions','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('262','view_cost-centers','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('263','create_cost-centers','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('264','edit_cost-centers','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('265','delete_cost-centers','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('266','view_accounting-general-ledger','web','2021-05-01 20:40:15','2021-05-01 20:40:15');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('267','print_accounting-general-ledger','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('268','export_accounting-general-ledger','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('269','view_fiscal-years','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('270','create_fiscal-years','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('271','edit_fiscal-years','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('272','delete_fiscal-years','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('273','view_trial-balance-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('274','print_trial-balance-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('275','export_trial-balance-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('276','view_balance-sheet-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('277','print_balance-sheet-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('278','export_balance-sheet-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('279','view_income-list-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('280','print_income-list-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('281','export_income-list-index','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('282','view_adverse-restrictions','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('283','create_adverse-restrictions','web','2021-05-01 20:40:16','2021-05-01 20:40:16');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('284','view_opening-balance','web','2021-05-01 20:40:17','2021-05-01 20:40:17');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('285','create_opening-balance','web','2021-05-01 20:40:17','2021-05-01 20:40:17');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('286','edit_opening-balance','web','2021-05-01 20:40:17','2021-05-01 20:40:17');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('287','delete_opening-balance','web','2021-05-01 20:40:17','2021-05-01 20:40:17');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('288','accounts-tree-index_account_nature_edit','web','2021-05-01 20:40:17','2021-05-01 20:40:17');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('289','purchase_invoices_active_tax','web','2021-05-01 20:40:17','2021-05-01 20:40:17');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('290','view_reservations','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('291','take_action_reservations','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('292','suppliers_create_attachment','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('293','suppliers_view_attachment','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('294','suppliers_delete_attachment','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('295','customers_create_attachment','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('296','customers_view_attachment','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('297','customers_delete_attachment','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('298','parts_create_attachment','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('299','parts_view_attachment','web','2021-05-01 20:40:18','2021-05-01 20:40:18');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('300','parts_delete_attachment','web','2021-05-01 20:40:19','2021-05-01 20:40:19');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('301','view_bank_exchange_permissions','web','2021-05-01 20:40:20','2021-05-01 20:40:20');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('302','create_bank_exchange_permissions','web','2021-05-01 20:40:20','2021-05-01 20:40:20');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('303','edit_bank_exchange_permissions','web','2021-05-01 20:40:20','2021-05-01 20:40:20');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('304','delete_bank_exchange_permissions','web','2021-05-01 20:40:20','2021-05-01 20:40:20');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('305','print_bank_exchange_permissions','web','2021-05-01 20:40:20','2021-05-01 20:40:20');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('306','export_bank_exchange_permissions','web','2021-05-01 20:40:20','2021-05-01 20:40:20');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('307','approve_bank_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('308','reject_bank_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('309','view_bank_receive_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('310','create_bank_receive_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('311','edit_bank_receive_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('312','delete_bank_receive_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('313','print_bank_receive_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('314','export_bank_receive_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('315','approve_bank_receive_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('316','reject_bank_receive_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('317','view_locker_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('318','create_locker_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('319','edit_locker_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('320','delete_locker_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('321','print_locker_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('322','export_locker_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('323','approve_locker_exchange_permissions','web','2021-05-01 20:40:21','2021-05-01 20:40:21');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('324','reject_locker_exchange_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('325','view_locker_receive_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('326','create_locker_receive_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('327','edit_locker_receive_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('328','delete_locker_receive_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('329','print_locker_receive_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('330','export_locker_receive_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('331','approve_locker_receive_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO permissions (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('332','reject_locker_receive_permissions','web','2021-05-01 20:40:22','2021-05-01 20:40:22');

INSERT INTO point_rules (`id`, `branch_id`, `points`, `amount`, `status`, `text_ar`, `text_en`, `deleted_at`, `created_at`, `updated_at`) VALUES 
('1','1','100','500.00','1','شريحة 1','type 1','','2021-05-01 23:28:10','2021-05-01 23:28:10');

INSERT INTO point_settings (`id`, `branch_id`, `points`, `amount`, `created_at`, `updated_at`) VALUES 
('1','1','10','100.00','2021-05-01 23:27:45','2021-05-01 23:27:45');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('2','2','3','3','1','1','800.00','800.00','amount','0.00','800.00','','','800','800.00','1','3','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('3','3','1','3','12','12','2322.00','2322.00','amount','0.00','27864.00','','','27864','27864.00','12','2','3','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('4','4','2','3','5','5','4000.00','4000.00','amount','0.00','20000.00','','','20000','20000.00','5','3','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('5','5','3','3','12','12','850.00','850.00','amount','0.00','10200.00','','','10200','10200.00','12','5','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('8','12','4','3','2','2','2500.00','2500.00','amount','0.00','5000.00','','','5000','5000.00','2','6','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('9','12','5','3','2','2','4000.00','4000.00','amount','0.00','8000.00','','','8000','8000.00','2','8','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('10','12','6','3','2','2','2000.00','2000.00','amount','0.00','4000.00','','','4000','4000.00','2','10','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('11','13','4','3','5','5','2500.00','2500.00','amount','0.00','12500.00','','','12500','12500.00','5','6','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('12','13','5','3','5','5','4000.00','4000.00','amount','0.00','20000.00','','','20000','20000.00','5','8','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('13','14','4','4','10','10','2500.00','2500.00','amount','0.00','25000.00','','','25000','25000.00','10','6','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('14','14','4','6','5','5','2500.00','2500.00','amount','0.00','12500.00','','','12500','12500.00','5','6','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('15','15','4','4','7','7','2500.00','2500.00','amount','0.00','17500.00','','','17500','17500.00','7','6','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('16','15','5','6','7','7','4000.00','4000.00','amount','0.00','28000.00','','','28000','28000.00','7','8','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('17','16','4','3','10','10','2500.00','2500.00','amount','0.00','25000.00','','','25000','25000.00','10','6','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('19','17','5','3','6','6','4000.00','4000.00','amount','0.00','24000.00','','','24000','24000.00','6','8','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('21','20','7','3','12','12','24000.00','24000.00','amount','0.00','288000.00','','','288000','288000.00','12','12','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('22','21','4','3','24','24','30000.00','30000.00','amount','0.00','720000.00','','','720000','720000.00','24','7','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('24','22','4','3','1','1','2500.00','2500.00','amount','0.00','2500.00','','','2500','2500.00','1','6','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('62','41','6','1','20','20','500.00','500.00','amount','0.00','10000.00','','','10000','10000.00','20','8','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('63','42','1','1','10','10','500.00','500.00','amount','0.00','5000.00','','','5000','5000.00','10','1','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('64','43','9','1','15','15','500.00','500.00','amount','0.00','7500.00','','','7500','7500.00','15','11','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('65','44','10','2','12','12','1000.00','1000.00','amount','0.00','12000.00','','','12000','12000.00','12','12','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('66','45','8','3','13','13','1000.00','1000.00','amount','0.00','13000.00','','','13000','13000.00','13','10','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('67','46','7','1','16','16','1500.00','1500.00','amount','0.00','24000.00','','','24000','24000.00','16','9','','0.00');

INSERT INTO purchase_invoice_items (`id`, `purchase_invoice_id`, `part_id`, `store_id`, `available_qty`, `purchase_qty`, `last_purchase_price`, `purchase_price`, `discount_type`, `discount`, `total_after_discount`, `created_at`, `updated_at`, `total_before_discount`, `subtotal`, `quantity`, `part_price_id`, `part_price_segment_id`, `tax`) VALUES 
('68','47','1','1','3','3','500.00','500.00','amount','0.00','1500.00','','','1500','1500.00','3','1','','0.00');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('1','20000001','','1','2021-05-03','19:45:00','cash','1','amount','0.00','500000.00','500000.00','2021-05-03 19:45:37','2021-05-05 09:31:39','2021-05-05 09:31:39','','','500000','','','amount','0.00','500000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('2','20000002','','1','2021-05-03','21:33:00','cash','1','amount','0.00','800.00','800.00','2021-05-03 21:33:39','2021-05-03 21:33:39','','','','800','','','amount','0.00','800.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('3','20000001','','1','2021-05-06','10:23:00','cash','1','amount','0.00','2322.00','2322.00','2021-05-06 10:24:15','2021-05-06 10:24:15','','','','2322','','','amount','0.00','2322.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('4','20000002','','1','2021-05-06','10:38:00','cash','1','amount','0.00','20000.00','20000.00','2021-05-06 10:39:28','2021-05-06 10:39:28','','','','20000','','','amount','0.00','20000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('5','20000003','','1','2021-05-07','21:04:00','cash','1','amount','0.00','10200.00','10200.00','2021-05-07 21:04:14','2021-05-07 21:04:14','','','','10200','','','amount','0.00','10200.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('9','20000004','','1','2021-05-08','04:16:00','cash','1','amount','0.00','20000.00','20000.00','2021-05-08 04:15:24','2021-05-08 04:15:53','2021-05-08 04:15:53','','','20000','','','amount','0.00','20000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('10','20000004','','1','2021-05-08','04:15:00','cash','1','amount','0.00','40000.00','40000.00','2021-05-08 04:16:06','2021-05-08 04:17:02','2021-05-08 04:17:02','','','40000','','','amount','0.00','40000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('12','20000001','','1','2021-05-09','20:34:00','cash','3','amount','0.00','17000.00','17000.00','2021-05-09 20:35:00','2021-05-09 20:35:00','','','','17000','','','amount','0.00','17000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('13','20000001','','1','2021-05-09','20:39:00','cash','2','amount','0.00','32500.00','32500.00','2021-05-09 20:39:45','2021-05-09 20:39:45','','','','32500','','','amount','0.00','32500.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('14','20000002','','1','2021-05-09','21:41:00','cash','2','amount','0.00','37500.00','37500.00','2021-05-09 21:42:49','2021-05-09 21:42:49','','','','37500','','','amount','0.00','37500.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('15','20000003','','1','2021-05-09','21:42:00','cash','2','amount','0.00','45500.00','45500.00','2021-05-09 21:43:07','2021-05-09 21:43:07','','','','45500','','','amount','0.00','45500.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('16','20000004','','1','2021-05-11','23:38:00','cash','1','amount','0.00','25000.00','25000.00','2021-05-11 23:39:10','2021-05-11 23:39:10','','','','25000','','','amount','0.00','25000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('17','20000005','','1','2021-05-11','23:43:00','cash','1','amount','0.00','24000.00','24000.00','2021-05-11 23:43:23','2021-05-11 23:43:35','','','','24000','','','amount','0.00','24000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('20','20000006','','1','2021-05-12','03:22:00','cash','1','amount','0.00','24000.00','24000.00','2021-05-12 03:22:51','2021-05-12 03:22:51','','','','24000','','','amount','0.00','24000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('21','20000007','','1','2021-05-12','04:20:00','cash','1','amount','0.00','60000.00','60000.00','2021-05-12 04:20:54','2021-05-12 04:20:54','','','','60000','','','amount','0.00','60000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('22','20000008','','1','2021-05-12','04:28:00','cash','1','amount','0.00','2500.00','2500.00','2021-05-12 04:28:34','2021-05-12 04:28:44','','','','2500','','','amount','0.00','2500.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('41','1','','1','2021-05-29','14:09:00','cash','1','amount','0.00','10000.00','10000.00','2021-05-29 14:09:34','2021-05-29 14:09:34','','','','10000','','','amount','0.00','10000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('42','2','','1','2021-05-29','14:09:00','cash','1','amount','0.00','5000.00','5000.00','2021-05-29 14:09:51','2021-05-29 14:09:51','','','','5000','','','amount','0.00','5000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('43','3','','1','2021-05-29','14:09:00','cash','1','amount','0.00','7500.00','7500.00','2021-05-29 14:10:07','2021-05-29 14:10:07','','','','7500','','','amount','0.00','7500.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('44','4','','1','2021-05-29','14:10:00','cash','1','amount','0.00','12000.00','12000.00','2021-05-29 14:10:32','2021-05-29 14:10:32','','','','12000','','','amount','0.00','12000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('45','5','','1','2021-05-29','14:10:00','cash','1','amount','0.00','13000.00','13000.00','2021-05-29 14:10:49','2021-05-29 14:10:49','','','','13000','','','amount','0.00','13000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('46','6','','1','2021-05-29','14:10:00','cash','1','amount','0.00','24000.00','24000.00','2021-05-29 14:11:09','2021-05-29 14:11:09','','','','24000','','','amount','0.00','24000.00','1');

INSERT INTO purchase_invoices (`id`, `invoice_number`, `supplier_id`, `branch_id`, `date`, `time`, `type`, `number_of_items`, `discount_type`, `discount`, `total`, `total_after_discount`, `created_at`, `updated_at`, `deleted_at`, `discount_group_value`, `is_discount_group_added`, `paid`, `remaining`, `is_returned`, `discount_group_type`, `tax`, `subtotal`, `is_opening_balance`) VALUES 
('47','7','','1','2021-06-07','03:28:00','cash','1','amount','0.00','1500.00','1500.00','2021-06-07 03:29:03','2021-06-07 03:29:03','','','','1500','','','amount','0.00','1500.00','1');

INSERT INTO purchase_quotation_executions (`id`, `purchase_quotation_id`, `date_from`, `date_to`, `status`, `notes`, `created_at`, `updated_at`) VALUES 
('1','1','2021-04-27','2021-06-05','finished','','2021-05-30 16:01:19','2021-05-31 14:00:39');

INSERT INTO purchase_quotation_item_taxes_fees (`id`, `item_id`, `tax_id`) VALUES 
('9','13','2');

INSERT INTO purchase_quotation_item_taxes_fees (`id`, `item_id`, `tax_id`) VALUES 
('10','13','3');

INSERT INTO purchase_quotation_items (`id`, `purchase_quotation_id`, `part_id`, `part_price_id`, `quantity`, `price`, `sub_total`, `discount`, `discount_type`, `total_after_discount`, `tax`, `total`, `active`, `created_at`, `updated_at`, `part_price_segment_id`) VALUES 
('12','1','1','1','1','500.00','500.00','0.00','amount','500.00','0.00','500.00','1','2021-05-31 13:40:43','2021-05-31 13:40:43','');

INSERT INTO purchase_quotation_items (`id`, `purchase_quotation_id`, `part_id`, `part_price_id`, `quantity`, `price`, `sub_total`, `discount`, `discount_type`, `total_after_discount`, `tax`, `total`, `active`, `created_at`, `updated_at`, `part_price_segment_id`) VALUES 
('13','1','2','3','2','100.00','200.00','0.00','amount','200.00','30.00','230.00','1','2021-05-31 13:40:43','2021-05-31 13:40:43','');

INSERT INTO purchase_quotation_items (`id`, `purchase_quotation_id`, `part_id`, `part_price_id`, `quantity`, `price`, `sub_total`, `discount`, `discount_type`, `total_after_discount`, `tax`, `total`, `active`, `created_at`, `updated_at`, `part_price_segment_id`) VALUES 
('14','2','9','11','3','500.00','1500.00','0.00','amount','1500.00','0.00','1500.00','1','2021-06-02 16:45:37','2021-06-02 16:45:37','');

INSERT INTO purchase_quotation_items (`id`, `purchase_quotation_id`, `part_id`, `part_price_id`, `quantity`, `price`, `sub_total`, `discount`, `discount_type`, `total_after_discount`, `tax`, `total`, `active`, `created_at`, `updated_at`, `part_price_segment_id`) VALUES 
('15','2','4','6','1','1000.00','1000.00','0.00','amount','1000.00','0.00','1000.00','1','2021-06-02 16:45:37','2021-06-02 16:45:37','');

INSERT INTO purchase_quotation_items (`id`, `purchase_quotation_id`, `part_id`, `part_price_id`, `quantity`, `price`, `sub_total`, `discount`, `discount_type`, `total_after_discount`, `tax`, `total`, `active`, `created_at`, `updated_at`, `part_price_segment_id`) VALUES 
('16','2','8','10','1','1000.00','1000.00','0.00','amount','1000.00','0.00','1000.00','1','2021-06-02 16:45:37','2021-06-02 16:45:37','');

INSERT INTO purchase_quotation_items_spare_parts (`id`, `item_id`, `spare_part_id`, `price`, `created_at`, `updated_at`) VALUES 
('1','14','14','500.00','','');

INSERT INTO purchase_quotation_items_spare_parts (`id`, `item_id`, `spare_part_id`, `price`, `created_at`, `updated_at`) VALUES 
('2','15','13','1000.00','','');

INSERT INTO purchase_quotation_items_spare_parts (`id`, `item_id`, `spare_part_id`, `price`, `created_at`, `updated_at`) VALUES 
('3','16','14','1500.00','','');

INSERT INTO purchase_quotation_supply_terms (`id`, `purchase_quotation_id`, `supply_term_id`) VALUES 
('1','1','3');

INSERT INTO purchase_quotation_supply_terms (`id`, `purchase_quotation_id`, `supply_term_id`) VALUES 
('2','1','2');

INSERT INTO purchase_quotation_taxes_fees (`id`, `purchase_quotation_id`, `tax_id`) VALUES 
('5','1','1');

INSERT INTO purchase_quotation_taxes_fees (`id`, `purchase_quotation_id`, `tax_id`) VALUES 
('6','2','1');

INSERT INTO purchase_quotation_taxes_fees (`id`, `purchase_quotation_id`, `tax_id`) VALUES 
('7','2','4');

INSERT INTO purchase_quotations (`id`, `number`, `branch_id`, `purchase_request_id`, `date`, `time`, `type`, `user_id`, `supplier_id`, `status`, `supply_date_from`, `supply_date_to`, `sub_total`, `discount`, `discount_type`, `total_after_discount`, `tax`, `total`, `library_path`, `created_at`, `updated_at`, `additional_payments`) VALUES 
('1','1','1','','2021-05-30','15:22:45','out_purchase_request','1','1','accept','2021-05-30','2021-05-30','730.00','0.00','amount','730.00','102.20','832.20','','2021-05-30 15:27:56','2021-05-31 13:40:43','0.00');

INSERT INTO purchase_quotations (`id`, `number`, `branch_id`, `purchase_request_id`, `date`, `time`, `type`, `user_id`, `supplier_id`, `status`, `supply_date_from`, `supply_date_to`, `sub_total`, `discount`, `discount_type`, `total_after_discount`, `tax`, `total`, `library_path`, `created_at`, `updated_at`, `additional_payments`) VALUES 
('2','2','1','6','2021-06-02','16:41:31','from_purchase_request','1','1','pending','2021-06-02','2021-06-02','3500.00','0.00','amount','3500.00','315.00','3815.00','','2021-06-02 16:45:37','2021-06-02 16:45:37','0.00');

INSERT INTO purchase_request_executions (`id`, `purchase_request_id`, `date_from`, `date_to`, `status`, `notes`, `created_at`, `updated_at`) VALUES 
('1','6','2021-05-01 00:00:00','2021-05-31 00:00:00','pending','','2021-05-31 13:55:31','2021-05-31 13:55:38');

INSERT INTO purchase_request_executions (`id`, `purchase_request_id`, `date_from`, `date_to`, `status`, `notes`, `created_at`, `updated_at`) VALUES 
('2','7','2021-05-01 00:00:00','2021-05-31 00:00:00','finished','','2021-05-31 13:58:33','2021-05-31 13:58:33');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','1','4','6','10','','2021-05-19 15:17:36','2021-05-28 15:09:39','2021-05-28 15:09:39');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('3','2','5','8','100','82','2021-05-19 15:18:26','2021-05-28 15:09:43','2021-05-28 15:09:43');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('6','3','4','6','2','2','2021-05-25 19:50:27','2021-05-28 15:09:47','2021-05-28 15:09:47');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('7','4','4','6','2','2','2021-05-25 19:55:05','2021-05-28 15:09:51','2021-05-28 15:09:51');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('10','5','4','6','1','1','2021-05-25 19:59:16','2021-05-28 15:09:55','2021-05-28 15:09:55');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('11','5','5','8','2','2','2021-05-25 19:59:16','2021-05-28 15:09:55','2021-05-28 15:09:55');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('18','6','9','11','3','3','2021-05-30 15:06:15','2021-05-30 15:07:31','');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('19','6','4','6','2','1','2021-05-30 15:06:15','2021-05-30 15:06:15','');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('20','6','8','10','1','1','2021-05-30 15:06:15','2021-05-30 15:07:31','');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('21','7','1','1','5','5','2021-05-30 15:06:25','2021-05-30 15:07:45','');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('22','7','2','3','10','10','2021-05-30 15:06:25','2021-05-30 15:06:25','');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('23','7','3','5','15','15','2021-05-30 15:06:25','2021-05-30 15:06:25','');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('24','8','1','1','2','','2021-06-07 03:45:11','2021-06-07 03:45:11','');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('25','9','1','1','4','','2021-06-07 20:17:45','2021-06-07 20:17:45','');

INSERT INTO purchase_request_items (`id`, `purchase_request_id`, `part_id`, `part_price_id`, `quantity`, `approval_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('26','9','2','3','3','3','2021-06-07 20:17:45','2021-06-07 20:17:45','');

INSERT INTO purchase_request_items_spare_parts (`id`, `purchase_request_item_id`, `spare_part_id`, `created_at`, `updated_at`) VALUES 
('17','18','14','','');

INSERT INTO purchase_request_items_spare_parts (`id`, `purchase_request_item_id`, `spare_part_id`, `created_at`, `updated_at`) VALUES 
('18','19','13','','');

INSERT INTO purchase_request_items_spare_parts (`id`, `purchase_request_item_id`, `spare_part_id`, `created_at`, `updated_at`) VALUES 
('19','20','14','','');

INSERT INTO purchase_request_items_spare_parts (`id`, `purchase_request_item_id`, `spare_part_id`, `created_at`, `updated_at`) VALUES 
('20','21','13','','');

INSERT INTO purchase_request_items_spare_parts (`id`, `purchase_request_item_id`, `spare_part_id`, `created_at`, `updated_at`) VALUES 
('21','22','13','','');

INSERT INTO purchase_request_items_spare_parts (`id`, `purchase_request_item_id`, `spare_part_id`, `created_at`, `updated_at`) VALUES 
('22','23','13','','');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','1','2021-05-19','15:16:17','1','1','ready_for_approval','normal','','','2021-05-01','2021-05-31','','','2021-05-19 15:17:03','2021-05-28 15:09:39','2021-05-28 15:09:39');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','2','2021-05-19','15:18:11','1','1','ready_for_approval','normal','','','2021-05-19','2021-05-19','','','2021-05-19 15:18:26','2021-05-28 15:09:43','2021-05-28 15:09:43');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('3','3','2021-05-25','19:34:55','1','1','accept_approval','normal','','','2021-05-25','2021-05-25','','','2021-05-25 19:35:04','2021-05-28 15:09:47','2021-05-28 15:09:47');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('4','4','2021-05-25','19:54:44','1','1','accept_approval','normal','','','2021-05-25','2021-05-25','','','2021-05-25 19:55:05','2021-05-28 15:09:51','2021-05-28 15:09:51');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('5','5','2021-05-25','19:57:59','1','1','accept_approval','normal','','','2021-05-25','2021-05-25','','','2021-05-25 19:58:48','2021-05-28 15:09:55','2021-05-28 15:09:55');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('6','1','2021-05-30','12:42:17','1','1','accept_approval','normal','','','2021-05-30','2021-05-30','','','2021-05-30 12:43:21','2021-05-30 15:07:31','');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('7','2','2021-05-30','14:46:08','1','1','accept_approval','normal','','','2021-05-30','2021-05-30','','','2021-05-30 15:06:04','2021-05-30 15:07:45','');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('8','3','2021-06-07','03:45:02','1','1','under_processing','normal','','','2021-06-07','2021-06-07','','','2021-06-07 03:45:11','2021-06-07 03:45:11','');

INSERT INTO purchase_requests (`id`, `number`, `date`, `time`, `branch_id`, `user_id`, `status`, `type`, `request_for`, `requesting_party`, `date_from`, `date_to`, `description`, `library_path`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('9','4','2021-06-07','20:17:25','1','1','under_processing','normal','','','2021-06-07','2021-06-07','','','2021-06-07 20:17:45','2021-06-07 20:17:45','');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('1','مدفوعات مرتجعات فاتوره شراء','purchase invoice return Payments','1','','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('2','مدفوعات فاتوره بيع','sales invoice Payments','1','','2','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('3','مدفوعات سلف','advances Payments','1','','3','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('4','مدفوعات فاتوره صيانة','card invoice Payments','1','','4','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('5','مدفوعات فاتوره صيانة','card invoice Payments','1','','5','1','2021-05-01 20:40:18','2021-05-01 20:40:18','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('6','مدفوعات مرتجعات فاتوره شراء','purchase invoice return Payments','1','','6','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('7','مدفوعات فاتوره بيع','sales invoice Payments','1','','7','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('8','مدفوعات سلف','advances Payments','1','','8','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('9','مدفوعات فاتوره صيانة','card invoice Payments','1','','9','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('10','مدفوعات مرتجعات فاتوره شراء','purchase invoice return Payments','1','','10','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('11','مدفوعات فاتوره بيع','sales invoice Payments','1','','11','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('12','مدفوعات سلف','advances Payments','1','','12','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('13','مدفوعات فاتوره صيانة','card invoice Payments','1','','13','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('14','مدفوعات مرتجعات فاتوره شراء','purchase invoice return Payments','1','','14','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('15','مدفوعات فاتوره بيع','sales invoice Payments','1','','15','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('16','مدفوعات سلف','advances Payments','1','','16','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('17','مدفوعات فاتوره صيانة','card invoice Payments','1','','17','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('18','مدفوعات مرتجعات فاتوره شراء','purchase invoice return Payments','1','','18','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('19','مدفوعات فاتوره بيع','sales invoice Payments','1','','19','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('20','مدفوعات سلف','advances Payments','1','','20','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('21','مدفوعات فاتوره صيانة','card invoice Payments','1','','21','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('22','مدفوعات مرتجعات فاتوره شراء','purchase invoice return Payments','1','','22','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('23','مدفوعات فاتوره بيع','sales invoice Payments','1','','23','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('24','مدفوعات سلف','advances Payments','1','','24','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('25','مدفوعات فاتوره صيانة','card invoice Payments','1','','25','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('26','مدفوعات مرتجعات فاتوره شراء','purchase invoice return Payments','1','','26','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('27','مدفوعات فاتوره بيع','sales invoice Payments','1','','27','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('28','مدفوعات سلف','advances Payments','1','','28','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO revenue_items (`id`, `item_ar`, `item_en`, `status`, `notes`, `revenue_id`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('29','مدفوعات فاتوره صيانة','card invoice Payments','1','','29','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('1','purchase invoice return','مرتجعات فاتوره شراء','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('2','sales invoice','فاتوره مبيعات','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('3','advances','سلف','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('4','card invoice','فاتوره صيانة','1','1','2021-05-01 20:39:57','2021-05-01 20:39:57','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('5','card invoice','فاتوره صيانة','1','1','2021-05-01 20:40:18','2021-05-01 20:40:18','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('6','purchase invoice return','مرتجعات فاتوره شراء','1','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('7','sales invoice','فاتوره مبيعات','1','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('8','advances','سلف','1','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('9','card invoice','فاتوره صيانة','1','2','2021-05-01 23:06:01','2021-05-01 23:06:01','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('10','purchase invoice return','مرتجعات فاتوره شراء','1','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('11','sales invoice','فاتوره مبيعات','1','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('12','advances','سلف','1','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('13','card invoice','فاتوره صيانة','1','3','2021-05-01 23:07:31','2021-05-01 23:07:31','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('14','purchase invoice return','مرتجعات فاتوره شراء','1','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('15','sales invoice','فاتوره مبيعات','1','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('16','advances','سلف','1','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('17','card invoice','فاتوره صيانة','1','4','2021-05-07 03:18:29','2021-05-07 03:18:29','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('18','purchase invoice return','مرتجعات فاتوره شراء','1','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('19','sales invoice','فاتوره مبيعات','1','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('20','advances','سلف','1','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('21','card invoice','فاتوره صيانة','1','5','2021-05-07 03:40:15','2021-05-07 03:40:15','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('22','purchase invoice return','مرتجعات فاتوره شراء','1','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('23','sales invoice','فاتوره مبيعات','1','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('24','advances','سلف','1','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('25','card invoice','فاتوره صيانة','1','6','2021-05-07 03:40:53','2021-05-07 03:40:53','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('26','purchase invoice return','مرتجعات فاتوره شراء','1','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('27','sales invoice','فاتوره مبيعات','1','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('28','advances','سلف','1','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO revenue_types (`id`, `type_en`, `type_ar`, `status`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `is_seeder`) VALUES 
('29','card invoice','فاتوره صيانة','1','7','2021-05-08 06:12:01','2021-05-08 06:12:01','','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('1','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('1','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('1','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('1','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('2','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('2','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('2','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('2','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('3','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('3','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('3','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('3','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('4','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('4','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('4','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('4','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('5','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('5','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('5','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('5','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('6','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('7','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('8','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('9','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('10','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('10','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('10','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('10','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('11','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('11','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('11','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('11','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('12','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('12','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('12','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('12','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('13','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('13','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('13','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('13','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('14','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('14','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('14','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('14','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('15','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('15','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('15','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('15','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('16','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('16','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('16','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('16','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('17','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('17','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('17','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('17','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('18','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('18','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('18','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('18','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('19','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('19','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('19','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('19','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('20','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('20','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('20','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('20','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('21','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('21','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('21','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('21','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('22','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('22','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('22','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('22','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('23','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('23','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('23','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('23','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('24','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('24','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('24','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('24','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('25','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('25','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('25','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('25','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('26','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('26','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('26','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('26','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('27','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('27','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('27','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('27','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('28','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('28','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('28','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('28','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('29','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('29','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('29','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('29','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('30','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('30','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('30','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('30','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('31','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('31','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('31','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('31','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('32','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('32','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('32','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('32','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('33','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('33','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('33','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('33','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('34','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('34','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('34','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('34','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('35','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('35','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('35','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('35','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('36','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('36','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('36','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('36','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('37','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('37','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('37','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('37','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('38','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('38','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('38','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('38','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('39','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('39','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('39','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('39','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('40','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('40','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('40','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('40','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('41','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('41','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('41','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('41','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('42','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('42','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('42','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('42','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('43','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('43','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('43','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('43','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('44','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('44','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('44','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('44','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('45','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('45','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('45','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('45','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('46','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('46','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('46','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('46','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('47','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('47','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('47','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('47','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('48','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('48','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('48','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('48','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('49','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('49','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('49','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('49','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('50','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('50','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('50','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('50','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('51','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('51','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('51','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('51','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('52','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('52','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('52','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('52','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('53','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('53','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('53','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('53','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('54','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('54','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('54','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('54','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('55','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('55','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('55','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('55','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('56','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('56','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('56','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('56','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('57','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('57','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('57','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('57','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('58','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('58','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('58','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('58','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('59','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('59','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('59','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('59','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('60','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('60','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('60','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('60','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('61','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('61','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('61','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('61','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('62','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('62','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('62','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('62','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('63','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('63','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('63','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('63','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('64','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('64','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('64','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('64','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('65','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('65','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('65','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('65','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('66','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('66','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('66','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('66','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('67','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('67','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('67','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('67','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('68','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('68','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('68','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('68','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('69','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('69','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('69','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('69','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('70','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('70','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('70','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('70','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('71','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('71','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('71','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('71','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('72','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('72','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('72','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('72','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('73','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('73','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('73','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('73','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('74','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('74','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('74','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('74','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('75','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('75','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('75','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('75','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('76','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('76','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('76','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('76','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('77','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('77','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('77','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('77','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('78','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('78','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('78','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('78','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('79','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('79','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('79','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('79','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('80','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('81','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('82','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('83','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('84','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('84','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('84','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('84','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('85','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('85','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('85','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('85','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('86','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('86','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('86','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('86','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('87','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('87','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('87','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('87','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('88','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('88','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('88','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('88','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('89','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('89','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('89','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('89','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('90','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('90','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('90','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('90','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('91','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('91','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('91','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('91','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('92','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('92','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('92','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('92','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('93','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('93','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('93','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('93','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('94','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('94','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('94','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('94','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('95','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('95','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('95','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('95','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('96','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('96','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('96','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('96','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('97','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('97','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('97','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('97','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('98','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('98','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('98','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('98','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('99','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('99','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('99','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('99','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('100','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('100','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('100','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('100','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('101','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('101','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('101','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('101','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('102','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('102','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('102','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('102','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('103','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('103','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('103','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('103','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('104','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('104','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('104','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('104','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('105','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('105','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('105','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('105','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('106','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('106','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('106','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('106','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('107','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('107','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('107','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('107','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('108','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('108','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('108','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('108','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('109','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('109','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('109','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('109','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('110','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('110','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('110','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('110','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('111','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('111','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('111','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('111','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('112','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('112','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('112','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('112','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('113','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('113','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('113','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('113','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('114','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('114','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('114','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('114','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('115','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('115','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('115','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('115','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('116','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('116','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('116','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('116','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('117','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('117','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('117','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('117','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('118','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('118','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('118','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('118','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('119','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('119','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('119','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('119','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('120','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('120','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('120','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('120','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('121','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('121','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('121','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('121','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('122','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('122','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('122','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('122','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('123','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('123','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('123','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('123','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('124','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('124','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('124','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('124','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('125','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('125','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('125','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('125','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('126','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('126','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('126','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('126','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('127','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('127','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('127','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('127','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('128','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('128','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('128','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('128','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('129','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('129','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('129','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('129','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('130','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('130','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('130','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('130','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('131','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('131','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('131','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('131','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('132','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('132','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('132','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('132','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('133','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('133','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('133','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('133','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('134','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('134','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('134','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('134','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('135','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('135','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('135','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('135','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('136','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('136','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('136','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('136','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('137','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('137','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('137','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('137','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('138','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('138','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('138','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('138','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('139','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('139','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('139','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('139','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('140','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('140','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('140','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('140','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('141','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('141','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('141','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('141','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('142','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('142','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('142','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('142','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('143','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('143','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('143','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('143','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('144','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('144','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('144','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('144','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('145','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('145','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('145','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('145','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('146','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('146','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('146','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('146','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('147','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('147','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('147','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('147','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('148','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('148','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('148','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('148','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('149','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('149','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('149','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('149','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('150','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('150','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('150','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('150','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('151','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('151','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('151','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('151','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('152','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('152','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('152','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('152','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('153','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('153','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('153','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('153','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('154','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('154','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('154','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('154','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('155','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('155','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('155','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('155','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('156','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('156','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('156','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('156','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('157','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('157','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('157','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('157','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('158','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('158','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('158','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('158','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('159','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('159','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('159','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('159','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('160','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('160','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('160','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('160','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('161','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('161','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('161','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('161','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('162','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('162','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('162','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('162','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('163','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('163','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('163','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('163','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('164','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('164','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('164','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('164','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('165','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('165','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('165','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('165','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('166','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('166','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('166','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('166','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('167','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('167','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('167','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('167','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('168','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('168','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('168','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('168','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('169','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('169','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('169','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('169','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('170','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('170','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('170','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('170','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('171','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('171','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('171','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('171','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('172','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('172','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('172','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('172','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('173','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('173','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('173','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('173','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('174','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('174','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('174','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('174','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('175','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('175','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('175','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('175','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('176','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('176','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('176','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('176','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('177','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('177','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('177','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('177','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('178','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('178','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('178','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('178','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('179','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('179','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('179','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('179','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('180','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('180','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('180','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('180','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('181','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('181','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('181','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('181','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('182','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('182','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('182','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('182','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('183','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('183','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('183','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('183','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('184','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('184','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('184','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('184','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('185','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('185','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('185','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('185','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('186','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('186','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('186','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('186','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('187','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('187','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('187','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('187','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('188','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('188','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('188','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('188','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('189','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('189','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('189','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('189','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('190','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('190','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('190','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('190','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('191','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('191','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('191','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('191','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('192','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('192','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('192','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('192','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('193','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('193','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('193','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('193','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('194','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('194','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('194','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('194','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('195','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('195','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('195','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('195','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('196','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('196','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('196','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('196','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('197','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('197','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('197','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('197','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('198','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('198','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('198','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('198','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('199','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('199','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('199','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('199','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('200','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('200','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('200','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('200','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('201','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('201','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('201','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('201','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('202','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('202','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('202','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('202','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('203','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('203','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('203','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('203','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('204','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('204','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('204','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('204','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('205','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('205','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('205','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('205','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('206','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('206','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('206','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('206','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('207','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('207','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('207','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('207','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('208','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('208','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('208','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('208','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('209','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('209','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('209','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('209','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('210','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('210','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('210','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('210','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('211','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('211','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('211','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('211','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('212','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('212','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('212','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('212','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('213','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('213','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('213','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('213','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('214','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('214','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('214','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('214','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('215','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('215','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('215','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('215','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('216','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('216','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('216','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('216','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('217','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('217','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('217','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('217','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('218','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('218','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('218','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('218','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('219','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('219','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('219','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('219','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('220','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('220','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('220','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('220','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('221','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('221','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('221','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('221','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('222','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('222','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('222','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('222','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('223','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('223','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('223','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('223','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('224','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('224','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('224','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('224','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('225','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('225','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('225','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('225','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('226','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('226','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('226','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('226','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('227','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('227','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('227','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('227','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('228','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('228','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('228','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('228','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('229','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('229','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('229','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('229','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('230','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('230','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('230','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('230','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('231','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('231','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('231','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('231','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('232','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('232','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('232','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('232','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('233','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('233','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('233','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('233','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('234','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('234','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('234','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('234','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('235','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('235','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('235','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('235','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('236','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('236','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('236','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('236','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('237','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('237','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('237','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('237','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('238','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('238','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('238','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('238','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('239','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('239','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('239','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('239','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('240','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('240','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('240','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('240','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('241','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('241','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('241','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('241','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('242','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('242','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('242','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('242','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('243','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('243','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('243','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('243','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('244','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('244','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('244','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('244','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('245','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('245','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('245','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('245','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('246','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('246','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('246','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('246','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('247','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('247','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('247','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('247','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('248','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('248','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('248','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('248','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('249','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('249','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('249','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('249','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('250','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('250','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('250','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('250','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('251','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('251','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('251','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('251','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('252','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('252','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('252','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('252','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('253','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('253','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('253','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('253','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('254','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('254','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('254','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('254','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('255','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('255','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('255','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('255','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('256','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('256','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('256','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('256','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('257','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('257','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('257','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('257','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('258','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('258','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('258','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('258','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('259','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('259','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('259','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('259','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('260','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('260','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('260','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('260','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('261','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('261','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('261','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('261','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('262','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('262','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('262','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('262','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('263','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('263','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('263','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('263','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('264','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('264','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('264','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('264','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('265','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('265','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('265','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('265','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('266','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('266','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('266','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('266','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('267','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('267','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('267','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('267','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('268','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('268','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('268','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('268','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('269','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('269','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('269','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('269','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('270','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('270','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('270','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('270','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('271','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('271','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('271','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('271','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('272','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('272','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('272','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('272','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('273','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('273','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('273','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('273','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('274','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('274','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('274','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('274','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('275','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('275','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('275','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('275','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('276','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('276','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('276','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('276','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('277','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('277','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('277','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('277','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('278','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('278','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('278','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('278','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('279','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('279','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('279','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('279','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('280','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('280','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('280','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('280','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('281','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('281','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('281','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('281','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('282','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('282','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('282','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('282','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('283','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('283','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('283','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('283','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('284','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('284','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('284','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('284','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('285','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('285','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('285','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('285','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('286','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('286','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('286','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('286','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('287','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('287','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('287','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('287','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('288','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('288','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('288','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('288','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('289','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('289','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('289','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('289','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('290','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('290','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('290','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('290','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('291','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('291','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('291','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('291','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('292','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('292','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('292','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('292','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('293','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('293','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('293','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('293','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('294','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('294','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('294','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('294','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('295','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('295','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('295','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('295','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('296','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('296','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('296','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('296','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('297','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('297','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('297','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('297','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('298','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('298','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('298','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('298','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('299','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('299','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('299','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('299','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('300','1');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('300','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('300','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('300','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('301','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('301','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('301','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('302','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('302','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('302','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('303','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('303','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('303','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('304','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('304','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('304','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('305','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('305','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('305','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('306','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('306','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('306','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('307','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('307','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('307','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('308','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('308','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('308','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('309','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('309','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('309','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('310','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('310','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('310','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('311','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('311','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('311','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('312','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('312','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('312','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('313','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('313','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('313','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('314','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('314','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('314','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('315','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('315','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('315','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('316','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('316','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('316','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('317','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('317','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('317','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('318','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('318','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('318','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('319','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('319','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('319','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('320','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('320','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('320','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('321','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('321','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('321','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('322','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('322','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('322','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('323','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('323','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('323','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('324','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('324','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('324','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('325','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('325','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('325','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('326','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('326','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('326','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('327','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('327','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('327','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('328','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('328','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('328','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('329','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('329','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('329','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('330','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('330','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('330','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('331','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('331','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('331','4');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('332','2');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('332','3');

INSERT INTO role_has_permissions (`permission_id`, `role_id`) VALUES 
('332','4');

INSERT INTO roles (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('1','super-admin','web','2021-05-01 20:40:02','2021-05-01 20:40:02');

INSERT INTO roles (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('2','إدارة فرع 1_Branch 1','web','2021-05-01 23:08:42','2021-05-01 23:08:42');

INSERT INTO roles (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('3','إدارة فرع 2_Branch 2','web','2021-05-01 23:09:11','2021-05-01 23:09:11');

INSERT INTO roles (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES 
('4','إدارة فرع 3_branch 3','web','2021-05-01 23:09:40','2021-05-01 23:09:40');

INSERT INTO service_packages (`id`, `name_ar`, `name_en`, `total_before_discount`, `total_after_discount`, `services_number`, `discount_type`, `discount_value`, `branch_id`, `service_id`, `created_at`, `updated_at`, `deleted_at`, `number_of_hours`, `number_of_min`, `q`) VALUES 
('1','باكيدج 1','p 1','2000','2000','1','value','','1','a:1:{i:0;s:1:\"1\";}','2021-05-31 16:31:02','2021-05-31 16:31:02','','20','10','a:1:{i:0;s:1:\"2\";}');

INSERT INTO service_types (`id`, `branch_id`, `name_ar`, `name_en`, `status`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','1','قسم 1','section 1','1','','2021-05-31 15:12:51','2021-05-31 15:12:51','');

INSERT INTO service_types (`id`, `branch_id`, `name_ar`, `name_en`, `status`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','1','قسم 2','section 2','1','','2021-05-31 15:13:02','2021-05-31 15:13:02','');

INSERT INTO services (`id`, `type_id`, `branch_id`, `name_en`, `name_ar`, `description_en`, `description_ar`, `status`, `price`, `hours`, `minutes`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','1','1','service 1','خدمة 1','','','1','1000.00','10','5','2021-05-31 15:13:19','2021-05-31 15:13:19','');

INSERT INTO settings (`id`, `sales_invoice_terms_ar`, `sales_invoice_status`, `created_at`, `updated_at`, `sales_invoice_terms_en`, `maintenance_terms_ar`, `maintenance_terms_en`, `maintenance_status`, `branch_id`, `invoice_setting`, `filter_setting`, `sell_from_invoice_status`, `lat`, `long`, `kilo_meter_price`, `quotation_terms_en`, `quotation_terms_ar`, `quotation_terms_status`) VALUES 
('1','<p style=\"text-align:center\"><span style=\"font-size:18px\">شروط وأحكام المبيعات</span></p>','1','2021-05-01 23:26:46','2021-05-01 23:26:46','<p style=\"text-align:center\"><span style=\"font-size:18px\">Terms &amp; Condition</span></p>','<p style=\"text-align:center\"><span style=\"font-size:18px\">شروط واحكام الصيانة</span></p>','<p style=\"text-align:center\"><span style=\"font-size:18px\">Terms &amp; Condition</span></p>

<div id=\"gtx-anchor\" style=\"height:15px; left:20px; position:absolute; top:22px; visibility:hidden; width:107.219px\">&nbsp;</div>

<div aria-describedby=\"bubble-2\" class=\"gtx-bubble jfk-bubble\" role=\"alertdialog\" style=\"left:59px; opacity:1; top:47px; visibility:visible\">
<div class=\"jfk-bubble-content-id\" id=\"bubble-2\">
<div id=\"gtx-host\" style=\"max-width:400px; min-width:200px\">&nbsp;</div>
</div>

<div aria-label=\"Close\" class=\"jfk-bubble-closebtn jfk-bubble-closebtn-id\" role=\"button\" tabindex=\"0\">&nbsp;</div>

<div class=\"jfk-bubble-arrow jfk-bubble-arrow-id jfk-bubble-arrowup\" style=\"left:53.5px\">
<div class=\"jfk-bubble-arrowimplbefore\">&nbsp;</div>

<div class=\"jfk-bubble-arrowimplafter\">&nbsp;</div>
</div>
</div>','1','1','1','1','old','31.55221','29.323212','200.00','<p style=\"text-align:center\"><span style=\"font-size:18px\">Terms &amp; Condition</span></p>','<p style=\"text-align:center\"><span style=\"font-size:18px\">شروط وأحكام عروض الأسعار&nbsp;</span></p>','1');

INSERT INTO settlement_items (`id`, `settlement_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('8','5','10','2','12','','5','1000.00','2021-05-29 22:22:00','2021-05-29 22:22:00');

INSERT INTO settlement_items (`id`, `settlement_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('9','6','10','3','12','','5','1000.00','2021-05-29 22:23:04','2021-05-29 22:23:04');

INSERT INTO settlement_items (`id`, `settlement_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('10','7','9','1','11','','2','500.00','2021-05-29 22:23:19','2021-05-29 22:23:19');

INSERT INTO settlement_items (`id`, `settlement_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('11','8','10','2','12','','2','1000.00','2021-05-29 22:23:33','2021-05-29 22:23:33');

INSERT INTO settlement_items (`id`, `settlement_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('12','9','1','2','1','','2','500.00','2021-06-04 18:21:30','2021-06-04 18:21:30');

INSERT INTO settlement_items (`id`, `settlement_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('13','10','1','2','1','','1','500.00','2021-06-07 03:39:23','2021-06-07 03:39:23');

INSERT INTO settlement_items (`id`, `settlement_id`, `part_id`, `store_id`, `part_price_id`, `part_price_segment_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES 
('14','11','1','2','1','','2','500.00','2021-06-07 17:46:03','2021-06-07 17:46:03');

INSERT INTO settlements (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('5','1','1','1','positive','2021-05-29','22:21:22','5000.00','','2021-05-29 22:22:00','2021-05-29 22:22:00','');

INSERT INTO settlements (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('6','1','1','2','positive','2021-05-29','22:22:51','5000.00','','2021-05-29 22:23:04','2021-05-29 22:23:04','');

INSERT INTO settlements (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('7','1','1','3','negative','2021-05-29','22:23:07','1000.00','','2021-05-29 22:23:19','2021-05-29 22:23:19','');

INSERT INTO settlements (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('8','1','1','4','negative','2021-05-29','22:23:21','2000.00','','2021-05-29 22:23:33','2021-05-29 22:23:33','');

INSERT INTO settlements (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('9','1','1','5','positive','2021-06-04','18:21:18','1000.00','','2021-06-04 18:21:30','2021-06-04 18:21:30','');

INSERT INTO settlements (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('10','1','1','6','positive','2021-06-07','03:39:13','500.00','','2021-06-07 03:39:23','2021-06-07 03:39:23','');

INSERT INTO settlements (`id`, `branch_id`, `user_id`, `number`, `type`, `date`, `time`, `total`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('11','1','1','7','positive','2021-06-07','17:45:56','1000.00','','2021-06-07 17:46:03','2021-06-07 17:46:03','');

INSERT INTO spare_part_units (`id`, `unit_ar`, `unit_en`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('4','طن','Tons','2021-05-28 21:04:54','2021-05-28 21:04:54','');

INSERT INTO spare_part_units (`id`, `unit_ar`, `unit_en`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('5','م3','M3','2021-05-28 21:05:06','2021-05-28 21:05:06','');

INSERT INTO spare_part_units (`id`, `unit_ar`, `unit_en`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('6','علبة','Box','2021-05-28 21:05:23','2021-05-28 21:05:23','');

INSERT INTO spare_part_units (`id`, `unit_ar`, `unit_en`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('7','مم','mm','2021-05-28 21:05:51','2021-05-28 21:05:51','');

INSERT INTO spare_part_units (`id`, `unit_ar`, `unit_en`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('8','سم','CM','2021-05-28 21:06:03','2021-05-28 21:06:03','');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('13','الكهرباء','Electricity','1','2021-05-28 20:25:48','2021-05-28 20:26:57','','1','');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('14','السباكة','Plumbing','1','2021-05-28 20:26:05','2021-05-28 20:27:03','','1','');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('16','الأسلاك','Wire','1','2021-05-28 20:53:09','2021-05-28 20:53:09','','1','13');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('17','القواطع','Cutters','1','2021-05-28 20:53:51','2021-05-28 20:53:51','','1','13');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('19','سويديكس','Swedex','1','2021-05-28 20:58:00','2021-05-28 20:58:00','','1','16');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('20','بتشينو','Bicchino','1','2021-05-28 20:58:38','2021-05-28 20:58:38','','1','17');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('21','فينوس','Venus','1','2021-05-28 20:59:02','2021-05-28 20:59:02','','1','17');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('22','أحواض','Basins','1','2021-05-28 21:00:17','2021-05-28 21:00:17','','1','14');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('23','بانيو','Bathtub','1','2021-05-28 21:00:34','2021-05-28 21:00:34','','1','14');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('24','خلاطات','Mixers','1','2021-05-28 21:01:09','2021-05-28 21:01:09','','1','14');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('25','احواض اينوفا','Innova','1','2021-05-28 21:01:57','2021-05-28 21:01:57','','1','22');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('26','بانيو وسط','Medium bathtub','1','2021-05-28 21:02:40','2021-05-28 21:02:40','','1','23');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('27','بانيو كبير','Large bathtub','1','2021-05-28 21:02:58','2021-05-28 21:02:58','','1','23');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('28','خلاطات دش','Shower mixers','1','2021-05-28 21:03:31','2021-05-28 21:03:31','','1','24');

INSERT INTO spare_parts (`id`, `type_ar`, `type_en`, `branch_id`, `created_at`, `updated_at`, `deleted_at`, `status`, `spare_part_id`) VALUES 
('29','خلاطات مطبخ','Kitchen mixers','1','2021-05-28 21:03:54','2021-05-28 21:03:54','','1','24');

INSERT INTO store_employee_histories (`id`, `store_id`, `employee_id`, `start`, `end`, `created_at`, `updated_at`) VALUES 
('1','3','1','2021-06-03','2021-06-03','2021-06-03 15:49:40','2021-06-03 15:49:40');

INSERT INTO store_employee_histories (`id`, `store_id`, `employee_id`, `start`, `end`, `created_at`, `updated_at`) VALUES 
('2','3','2','2021-06-10','2021-06-03','2021-06-03 15:49:40','2021-06-03 15:49:40');

INSERT INTO store_transfer_items (`id`, `store_transfer_id`, `part_id`, `quantity`, `created_at`, `updated_at`, `deleted_at`, `new_part_id`, `part_price_id`, `part_price_segment_id`, `price`, `total`) VALUES 
('1','1','1','5','2021-05-29 14:31:25','2021-05-29 14:31:25','','','1','','500.00','2500.00');

INSERT INTO store_transfer_items (`id`, `store_transfer_id`, `part_id`, `quantity`, `created_at`, `updated_at`, `deleted_at`, `new_part_id`, `part_price_id`, `part_price_segment_id`, `price`, `total`) VALUES 
('2','2','10','5','2021-05-29 14:31:54','2021-05-29 14:31:54','','','12','','1000.00','5000.00');

INSERT INTO store_transfer_items (`id`, `store_transfer_id`, `part_id`, `quantity`, `created_at`, `updated_at`, `deleted_at`, `new_part_id`, `part_price_id`, `part_price_segment_id`, `price`, `total`) VALUES 
('5','5','1','4','2021-05-29 22:25:11','2021-05-29 22:25:11','','','1','','500.00','2000.00');

INSERT INTO store_transfer_items (`id`, `store_transfer_id`, `part_id`, `quantity`, `created_at`, `updated_at`, `deleted_at`, `new_part_id`, `part_price_id`, `part_price_segment_id`, `price`, `total`) VALUES 
('6','6','6','2','2021-06-02 22:28:21','2021-06-02 22:28:21','','','8','','500.00','1000.00');

INSERT INTO store_transfer_items (`id`, `store_transfer_id`, `part_id`, `quantity`, `created_at`, `updated_at`, `deleted_at`, `new_part_id`, `part_price_id`, `part_price_segment_id`, `price`, `total`) VALUES 
('7','7','1','1','2021-06-07 03:36:03','2021-06-07 03:36:03','','','1','','500.00','500.00');

INSERT INTO store_transfers (`id`, `transfer_number`, `transfer_date`, `store_from_id`, `store_to_id`, `created_at`, `updated_at`, `deleted_at`, `branch_id`, `user_id`, `time`, `total`, `description`) VALUES 
('1','1','2021-05-29','1','2','2021-05-29 14:31:25','2021-05-29 14:31:25','','1','1','14:31:07','2500.00','');

INSERT INTO store_transfers (`id`, `transfer_number`, `transfer_date`, `store_from_id`, `store_to_id`, `created_at`, `updated_at`, `deleted_at`, `branch_id`, `user_id`, `time`, `total`, `description`) VALUES 
('2','2','2021-05-29','2','3','2021-05-29 14:31:54','2021-05-29 14:31:54','','1','1','14:31:40','5000.00','');

INSERT INTO store_transfers (`id`, `transfer_number`, `transfer_date`, `store_from_id`, `store_to_id`, `created_at`, `updated_at`, `deleted_at`, `branch_id`, `user_id`, `time`, `total`, `description`) VALUES 
('5','3','2021-05-29','1','2','2021-05-29 22:25:11','2021-05-29 22:25:11','','1','1','22:24:18','2000.00','');

INSERT INTO store_transfers (`id`, `transfer_number`, `transfer_date`, `store_from_id`, `store_to_id`, `created_at`, `updated_at`, `deleted_at`, `branch_id`, `user_id`, `time`, `total`, `description`) VALUES 
('6','4','2021-06-02','1','2','2021-06-02 22:28:21','2021-06-02 22:28:21','','1','1','22:28:08','1000.00','');

INSERT INTO store_transfers (`id`, `transfer_number`, `transfer_date`, `store_from_id`, `store_to_id`, `created_at`, `updated_at`, `deleted_at`, `branch_id`, `user_id`, `time`, `total`, `description`) VALUES 
('7','5','2021-06-07','1','2','2021-06-07 03:36:03','2021-06-07 03:36:03','','1','1','03:35:26','500.00','');

INSERT INTO stores (`id`, `name_ar`, `name_en`, `employees_ids`, `store_phone`, `store_address`, `note`, `branch_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('1','مخزن موقع الإسكان الإجتماعي','Social Housing Site Store','a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}','01013046794','مصر الجديدة','','1','2021-05-28 20:18:43','2021-05-28 20:18:43','');

INSERT INTO stores (`id`, `name_ar`, `name_en`, `employees_ids`, `store_phone`, `store_address`, `note`, `branch_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('2','مخزن رئيسي لكل المواقع','main store','a:1:{i:0;s:1:\"1\";}','01154879416','العاصمة الإدارية','','1','2021-05-28 20:19:17','2021-05-28 20:19:17','');

INSERT INTO stores (`id`, `name_ar`, `name_en`, `employees_ids`, `store_phone`, `store_address`, `note`, `branch_id`, `created_at`, `updated_at`, `deleted_at`) VALUES 
('3','مخزن موقع فلل مصر الجديدة','Heliopolis Villas site store','a:1:{i:0;s:1:\"2\";}','01013046794','مصر الجديدة','','1','2021-05-28 20:20:16','2021-05-28 20:20:16','');

INSERT INTO supplier_contacts (`id`, `supplier_id`, `phone_1`, `phone_2`, `address`, `created_at`, `updated_at`, `name`) VALUES 
('1','2','15415411515','1212','','2021-05-05 05:01:32','2021-05-07 03:17:41','xcxc');

INSERT INTO supplier_contacts (`id`, `supplier_id`, `phone_1`, `phone_2`, `address`, `created_at`, `updated_at`, `name`) VALUES 
('3','5','41545','4545','','2021-05-07 03:35:21','2021-05-07 03:35:46','fddf');

INSERT INTO supplier_contacts (`id`, `supplier_id`, `phone_1`, `phone_2`, `address`, `created_at`, `updated_at`, `name`) VALUES 
('4','8','4545','','','2021-05-12 07:47:49','2021-05-12 07:47:49','gfg');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('1','مصر','Egypt','1','1','amount','0.00','','2021-05-05 03:32:51','2021-05-05 03:33:04','2021-05-05 03:33:04','');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('2','مجموعة 1','group 1','1','1','amount','0.00','','2021-05-05 04:18:19','2021-05-05 04:18:19','','');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('3','مجموعة 2','group 2','1','1','amount','0.00','','2021-05-05 04:18:32','2021-05-05 04:18:32','','');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('4','مجموعة 3','group 3','1','1','amount','0.00','','2021-05-05 04:18:44','2021-05-05 04:18:44','','');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('5','مجموعة 4','group 4','1','1','amount','0.00','','2021-05-05 04:18:57','2021-05-05 04:18:57','','');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('6','مجموعة 11','group 11','1','1','amount','0.00','','2021-05-05 04:19:37','2021-05-05 04:19:37','','2');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('7','مجموعة 111','group 111','1','1','amount','0.00','','2021-05-05 04:28:01','2021-05-05 04:28:01','','6');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('8','مجموعة 22','group 22','1','1','amount','0.00','','2021-05-05 04:28:15','2021-05-05 04:28:15','','3');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('9','مجموعة 33','group 33','1','1','amount','0.00','','2021-05-05 04:28:30','2021-05-05 04:28:30','','4');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('10','مجموعة 333','group 333','1','1','amount','0.00','','2021-05-05 04:28:44','2021-05-05 04:28:44','','9');

INSERT INTO supplier_groups (`id`, `name_ar`, `name_en`, `branch_id`, `status`, `discount_type`, `discount`, `description`, `created_at`, `updated_at`, `deleted_at`, `supplier_group_id`) VALUES 
('11','مصر','pound','2','1','amount','0.00','','2021-05-05 04:31:51','2021-05-05 04:32:14','2021-05-05 04:32:14','');

INSERT INTO suppliers (`id`, `name_en`, `name_ar`, `branch_id`, `country_id`, `city_id`, `area_id`, `phone_1`, `phone_2`, `address`, `type`, `email`, `fax`, `commercial_number`, `tax_card`, `status`, `funds_for`, `funds_on`, `description`, `created_at`, `updated_at`, `deleted_at`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `main_groups_id`, `sub_groups_id`, `supplier_type`, `identity_number`) VALUES 
('1','sup 1','مورد 1','1','1','1','1','','','','person','','','','','1','0.00','0.00','','2021-05-05 04:37:04','2021-05-12 07:25:09','','','','','0.00','','a:2:{i:0;s:1:\"2\";i:1;s:1:\"3\";}','','both_together','');

INSERT INTO suppliers (`id`, `name_en`, `name_ar`, `branch_id`, `country_id`, `city_id`, `area_id`, `phone_1`, `phone_2`, `address`, `type`, `email`, `fax`, `commercial_number`, `tax_card`, `status`, `funds_for`, `funds_on`, `description`, `created_at`, `updated_at`, `deleted_at`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `main_groups_id`, `sub_groups_id`, `supplier_type`, `identity_number`) VALUES 
('2','محمد','محمد ايمن','1','1','1','1','+201013046794','','القاهره','company','s1@s1.com','4154151','545454','','1','0.00','0.00','','2021-05-05 05:01:31','2021-05-14 05:05:15','','','','','0.00','','a:2:{i:0;s:1:\"3\";i:1;s:1:\"4\";}','','supplier','');

INSERT INTO suppliers (`id`, `name_en`, `name_ar`, `branch_id`, `country_id`, `city_id`, `area_id`, `phone_1`, `phone_2`, `address`, `type`, `email`, `fax`, `commercial_number`, `tax_card`, `status`, `funds_for`, `funds_on`, `description`, `created_at`, `updated_at`, `deleted_at`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `main_groups_id`, `sub_groups_id`, `supplier_type`, `identity_number`) VALUES 
('3','sup 3','مورد 3','1','','1','1','','','','person','','','','','1','0.00','0.00','','2021-05-05 05:04:47','2021-05-05 05:05:03','2021-05-05 05:05:03','','','','0.00','','a:1:{i:0;s:1:\"5\";}','','both_together','');

INSERT INTO suppliers (`id`, `name_en`, `name_ar`, `branch_id`, `country_id`, `city_id`, `area_id`, `phone_1`, `phone_2`, `address`, `type`, `email`, `fax`, `commercial_number`, `tax_card`, `status`, `funds_for`, `funds_on`, `description`, `created_at`, `updated_at`, `deleted_at`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `main_groups_id`, `sub_groups_id`, `supplier_type`, `identity_number`) VALUES 
('4','fdfd','dfdf','1','','1','1','','','','person','','','','','1','0.00','0.00','','2021-05-05 05:05:17','2021-05-05 05:05:26','2021-05-05 05:05:26','','','','0.00','','a:1:{i:0;s:1:\"2\";}','a:1:{i:0;s:1:\"6\";}','both_together','');

INSERT INTO suppliers (`id`, `name_en`, `name_ar`, `branch_id`, `country_id`, `city_id`, `area_id`, `phone_1`, `phone_2`, `address`, `type`, `email`, `fax`, `commercial_number`, `tax_card`, `status`, `funds_for`, `funds_on`, `description`, `created_at`, `updated_at`, `deleted_at`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `main_groups_id`, `sub_groups_id`, `supplier_type`, `identity_number`) VALUES 
('5','John','John mike','1','','1','1','07456985655','','74 STATE ST','person','admin@admin.com','','','','1','0.00','0.00','','2021-05-07 03:35:21','2021-05-08 19:58:44','2021-05-08 19:58:44','','','','0.00','','a:3:{i:0;s:1:\"3\";i:1;s:1:\"4\";i:2;s:1:\"5\";}','a:3:{i:0;s:1:\"8\";i:1;s:1:\"9\";i:2;s:2:\"10\";}','both_together','');

INSERT INTO suppliers (`id`, `name_en`, `name_ar`, `branch_id`, `country_id`, `city_id`, `area_id`, `phone_1`, `phone_2`, `address`, `type`, `email`, `fax`, `commercial_number`, `tax_card`, `status`, `funds_for`, `funds_on`, `description`, `created_at`, `updated_at`, `deleted_at`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `main_groups_id`, `sub_groups_id`, `supplier_type`, `identity_number`) VALUES 
('6','Mohammed','Mohammed Hassan','1','','1','1','','','شارع احمد عرابي','person','mohammedhassan1020303030@gmail.com','','','','1','0.00','0.00','','2021-05-08 03:48:31','2021-05-08 19:58:44','2021-05-08 19:58:44','','','','0.00','','a:1:{i:0;s:1:\"2\";}','a:1:{i:0;s:1:\"6\";}','both_together','');

INSERT INTO suppliers (`id`, `name_en`, `name_ar`, `branch_id`, `country_id`, `city_id`, `area_id`, `phone_1`, `phone_2`, `address`, `type`, `email`, `fax`, `commercial_number`, `tax_card`, `status`, `funds_for`, `funds_on`, `description`, `created_at`, `updated_at`, `deleted_at`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `main_groups_id`, `sub_groups_id`, `supplier_type`, `identity_number`) VALUES 
('7','Mohammed222','Mohammed Hassan22','1','','1','1','','','شارع احمد عرابي','person','','','','','1','0.00','0.00','','2021-05-08 03:49:34','2021-05-08 19:58:44','2021-05-08 19:58:44','','','','0.00','','a:1:{i:0;s:1:\"2\";}','','both_together','');

INSERT INTO suppliers (`id`, `name_en`, `name_ar`, `branch_id`, `country_id`, `city_id`, `area_id`, `phone_1`, `phone_2`, `address`, `type`, `email`, `fax`, `commercial_number`, `tax_card`, `status`, `funds_for`, `funds_on`, `description`, `created_at`, `updated_at`, `deleted_at`, `tax_number`, `lat`, `long`, `maximum_fund_on`, `library_path`, `main_groups_id`, `sub_groups_id`, `supplier_type`, `identity_number`) VALUES 
('8','John','John mike','1','','1','1','7456985655','','74 STATE ST','person','','','','','1','0.00','0.00','','2021-05-12 07:47:49','2021-05-26 11:13:21','','','','','0.00','','','','both_together','1541544444');

INSERT INTO supply_terms (`id`, `term_en`, `term_ar`, `type`, `branch_id`, `status`, `for_purchase_quotation`, `created_at`, `updated_at`) VALUES 
('1','Terms Terms Terms Terms Terms Terms Terms Terms Terms Terms','الشروط عربي الشروط عربي الشروط عربي الشروط عربي الشروط عربي','supply','1','','','2021-05-30 13:15:50','2021-05-30 13:35:19');

INSERT INTO supply_terms (`id`, `term_en`, `term_ar`, `type`, `branch_id`, `status`, `for_purchase_quotation`, `created_at`, `updated_at`) VALUES 
('2','terms terms terms terms terms terms terms terms','شروط السداد شروط السداد شروط السداد شروط السداد شروط السداد','payment','1','1','1','2021-05-30 13:19:10','2021-05-30 13:19:10');

INSERT INTO supply_terms (`id`, `term_en`, `term_ar`, `type`, `branch_id`, `status`, `for_purchase_quotation`, `created_at`, `updated_at`) VALUES 
('3','en en en en en en en en en en en','عربي عربي عربي عربي عربي عربي عربي عربي','supply','1','1','1','2021-05-30 13:24:26','2021-05-30 13:24:26');

INSERT INTO supply_terms (`id`, `term_en`, `term_ar`, `type`, `branch_id`, `status`, `for_purchase_quotation`, `created_at`, `updated_at`) VALUES 
('4','terms 1 terms 1 terms 1 terms 1 terms 1','شروط 1 شروط 1 شروط 1 شروط 1 شروط 1 شروط 1','payment','1','1','1','2021-05-30 13:24:54','2021-05-30 13:24:54');

INSERT INTO taxes_fees (`id`, `name_ar`, `name_en`, `tax_type`, `active_services`, `active_invoices`, `active_offers`, `branch_id`, `value`, `created_at`, `updated_at`, `deleted_at`, `active_purchase_invoice`, `type`, `on_parts`, `purchase_quotation`, `execution_time`) VALUES 
('1','ضريبة مضافة','VAT','percentage','1','1','1','1','14','2021-05-01 23:24:22','2021-05-30 14:38:16','','1','tax','','1','after_discount');

INSERT INTO taxes_fees (`id`, `name_ar`, `name_en`, `tax_type`, `active_services`, `active_invoices`, `active_offers`, `branch_id`, `value`, `created_at`, `updated_at`, `deleted_at`, `active_purchase_invoice`, `type`, `on_parts`, `purchase_quotation`, `execution_time`) VALUES 
('2','ضريبة صنف 1','item 1','percentage','','','','1','5','2021-05-03 07:13:31','2021-05-03 07:13:31','','','tax','1','','after_discount');

INSERT INTO taxes_fees (`id`, `name_ar`, `name_en`, `tax_type`, `active_services`, `active_invoices`, `active_offers`, `branch_id`, `value`, `created_at`, `updated_at`, `deleted_at`, `active_purchase_invoice`, `type`, `on_parts`, `purchase_quotation`, `execution_time`) VALUES 
('3','ضريبة صنف 2','item 2','percentage','','','','1','10','2021-05-03 07:13:49','2021-05-03 07:13:49','','','tax','1','','after_discount');

INSERT INTO taxes_fees (`id`, `name_ar`, `name_en`, `tax_type`, `active_services`, `active_invoices`, `active_offers`, `branch_id`, `value`, `created_at`, `updated_at`, `deleted_at`, `active_purchase_invoice`, `type`, `on_parts`, `purchase_quotation`, `execution_time`) VALUES 
('4','ضريبة خصم','discount tax','percentage','1','1','1','1','-5','2021-05-30 14:38:53','2021-06-02 01:28:04','','1','tax','','1','after_discount');

INSERT INTO taxes_fees (`id`, `name_ar`, `name_en`, `tax_type`, `active_services`, `active_invoices`, `active_offers`, `branch_id`, `value`, `created_at`, `updated_at`, `deleted_at`, `active_purchase_invoice`, `type`, `on_parts`, `purchase_quotation`, `execution_time`) VALUES 
('5','مدفوعات 1','p 1','amount','','','','1','1000','2021-06-03 16:19:45','2021-06-03 16:19:45','','','additional_payments','','1','after_discount');

INSERT INTO taxes_fees (`id`, `name_ar`, `name_en`, `tax_type`, `active_services`, `active_invoices`, `active_offers`, `branch_id`, `value`, `created_at`, `updated_at`, `deleted_at`, `active_purchase_invoice`, `type`, `on_parts`, `purchase_quotation`, `execution_time`) VALUES 
('6','مدفوعات 2','p 2','percentage','','','','1','10','2021-06-03 17:25:56','2021-06-03 17:26:10','','','additional_payments','','1','after_discount');

INSERT INTO users (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `status`, `super_admin`, `username`, `branch_id`, `image`, `theme`, `is_admin_branch`) VALUES 
('1','superadmin','superadmin@superadmin.com','00201013046794','','$2y$10$h18w/WZ/7SIIQOf0kE5iHeUAgQTN.AXI.Ngmd0UUeBIqVrxqSSQJq','','2021-05-01 20:39:57','2021-05-19 14:34:11','','1','1','superadmin','1','','dark-green','');

INSERT INTO users (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `status`, `super_admin`, `username`, `branch_id`, `image`, `theme`, `is_admin_branch`) VALUES 
('2','admin1','admin1@admin1.com','00201013046794','','$2y$10$YS8R8fFxFdoQaLty0ZAe/uQA34MBHjwfFfpDmFz/31ewWuessXHpC','','2021-05-01 23:17:09','2021-05-01 23:17:09','','1','','admin1','1','','dark-blue','');

INSERT INTO users (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `status`, `super_admin`, `username`, `branch_id`, `image`, `theme`, `is_admin_branch`) VALUES 
('3','admin2','admin2@admin2.com','002010000000','','$2y$10$EwYJEQ0bQdRKyaxrrlLGTOPGoBYN2rIc5w7wO1IZ4Qk1ffw4glHQm','','2021-05-01 23:17:38','2021-05-10 21:37:40','','1','','admin2','2','','dark-blue','');

INSERT INTO users (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `status`, `super_admin`, `username`, `branch_id`, `image`, `theme`, `is_admin_branch`) VALUES 
('4','admin3','admin3@admin3.com','012121111111','','$2y$10$QtR6Pq1Uy1bKqdcIRRmkIOmZAaZp6JkLq54GvmPlUq5YgWkzKtba2','','2021-05-01 23:21:40','2021-05-16 03:53:02','','1','','admin3','3','','dark-blue','');

INSERT INTO work_cards (`id`, `card_number`, `branch_id`, `customer_id`, `car_id`, `created_by`, `receive_car_status`, `status`, `receive_car_date`, `receive_car_time`, `delivery_car_status`, `delivery_car_date`, `delivery_car_time`, `created_at`, `updated_at`, `deleted_at`, `note`) VALUES 
('1','1','1','1','1','1','1','processing','2021-05-31','06:51:00','1','2021-05-31','06:51:00','2021-05-31 18:51:56','2021-05-31 19:11:37','','');
