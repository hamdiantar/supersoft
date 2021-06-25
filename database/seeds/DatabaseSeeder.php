<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BranchSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(BranchRolesSeeder::class);
        $this->call(StoreTransferPermissions::class);
        $this->call(TradingAccountSeed::class);
        $this->call(PermissionsV2Seeder::class);
        $this->call(permissionsV3Seeder::class);
        $this->call(PointsPermissionsSeeder::class);
        $this->call(AccountingModulePermissions::class);
        $this->call(OpeningBalancePermissions::class);
        $this->call(AccountTreeNewEditPermission::class);
        $this->call(ActiveTaxesSeeder::class);
        $this->call(ReservationPermission::class);
        $this->call(RevenueCardInvoiceSeeder::class);
        $this->call(AttachmentPermissionsSeeder::class);
        $this->call(MoneyBankPermissions::class);
        $this->call(MoneyLockerPermissions::class);
        $this->call(ConcessionItemsSeeder::class);
        $this->call(ConcessionItemsV2Seeder::class);
        $this->call(ConcessionItemsV3Seeder::class);
        $this->call(ConcessionItemsV4Seeder::class);
    }
}
