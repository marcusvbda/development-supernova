<?php

use Database\Seeders\PermissionSeeder;
use Database\Seeders\StartUpSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->createAccessGroups();
        $this->createUsers();
        $this->createPermissions();
        (new StartUpSeeder())->run();
        $aclSeeder = new PermissionSeeder();
        $aclSeeder->makePermissions('Grupos de Acesso', 'access-groups');
        $aclSeeder->makePermissions('Usuários', 'users');
    }

    private function createPermissions()
    {
        $this->createTable('access_group_permissions', function (Blueprint $table) {
            $table = $this->addForeignKey($table, 'access_group_id', 'access_groups', 'id');
            $table = $this->addForeignKey($table, 'permission_id', 'permissions', 'id');
        }, ["id" => false, "timestamps" => false, "softDeletes" => false]);

        Schema::create("permissions", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('key');
            $table->string('type');
        });

        Schema::create("access_group_permissions", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger("access_group_id");
            $table->foreign("access_group_id")
                ->references("id")
                ->on("access_groups")
                ->onDelete("cascade");
            $table->unsignedBigInteger("permission_id");
            $table->foreign("permission_id")
                ->references("id")
                ->on("permissions")
                ->onDelete("cascade");
            $table->primary(["access_group_id", "permission_id"]);
        });
    }

    private function createAccessGroups()
    {
        Schema::create("access_groups", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    private function createUsers()
    {
        Schema::create("users", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('role')->default("user");
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('position')->nullable();
            $table->jsonb('avatar')->nullable();
            $table->string('password')->nullable();
            $table->unsignedBigInteger("access_group_id")->nullable();
            $table->foreign("access_group_id")
                ->references("id")
                ->on("access_groups")
                ->onDelete("set null");
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("users");
        Schema::dropIfExists("access_groups");
    }
};
