<?php

use App\Models\Store;
use Illuminate\Database\Migrations\Migration;

class EmptyStoresTable extends Migration
{
    public function up(): void
    {
        Store::query()->forceDelete();
    }

    public function down(): void
    {
    }
}
