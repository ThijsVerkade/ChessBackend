<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            <<<OED
CREATE TABLE IF NOT EXISTS `aggregate_game` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `event_id` BINARY(16) NOT NULL,
    `aggregate_root_id` BINARY(16) NOT NULL,
    `version` int(20) unsigned NULL,
    `payload` varchar(16001) NOT NULL,
    PRIMARY KEY (`id` ASC),
    KEY `reconstitution` (`aggregate_root_id`, `version` ASC)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB;
OED
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('aggregate_game');
    }
};
