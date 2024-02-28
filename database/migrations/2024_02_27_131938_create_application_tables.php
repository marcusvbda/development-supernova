<?php

use Database\Seeders\StartUpSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use marcusvbda\supernova\seeders\PermissionSeeder;

return new class extends Migration
{
    public function up(): void
    {
        $this->createSquads();
        $this->createTeams();
        $this->createCustomers();
        $this->createProjects();
        $this->createCompetences();
        $this->updateUsers();
        (new StartUpSeeder())->run();
        $aclSeeder = new PermissionSeeder();
        $aclSeeder->makePermissions('Squads', 'squads');
        $aclSeeder->makePermissions('Clientes', 'customer');
        $aclSeeder->makePermissions('Projetos', 'projects');
    }

    private function createCompetences()
    {
        Schema::create("competences", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });


        Schema::create("skills", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger("competence_id");
            $table->foreign("competence_id")
                ->references("id")
                ->on("competences")
                ->onDelete("cascade");
            $table->timestamps();
        });
    }

    private function createProjects()
    {
        Schema::create("projects", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('board')->nullable();
            $table->unsignedBigInteger("customer_id");
            $table->foreign("customer_id")
                ->references("id")
                ->on("customers")
                ->onDelete("cascade");
            $table->timestamps();
        });
    }

    private function createCustomers()
    {
        Schema::create("teams", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    private function createTeams()
    {
        Schema::create("customers", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('contact')->nullable();
            $table->string('email_contact')->nullable();
            $table->string('phone_contact')->nullable();
            $table->timestamps();
        });
    }

    private function createSquads()
    {
        Schema::create("squads", function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    private function updateUsers()
    {
        Schema::table("users", function (Blueprint $table) {
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('position')->nullable();
            $table->unsignedBigInteger("team_id")->nullable();
            $table->foreign("team_id")
                ->references("id")
                ->on("teams")
                ->onDelete("set null");
            $table->unsignedBigInteger("squad_id")->nullable();
            $table->foreign("squad_id")
                ->references("id")
                ->on("squads")
                ->onDelete("set null");
        });

        Schema::create("team_users", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("team_id");
            $table->foreign("team_id")
                ->references("id")
                ->on("teams")
                ->onDelete("cascade");
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->timestamps();
        });

        Schema::create("squad_users", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("squad_id");
            $table->foreign("squad_id")
                ->references("id")
                ->on("squads")
                ->onDelete("cascade");
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("customer");
        Schema::dropIfExists("teams");
        Schema::dropIfExists("squads");
        Schema::dropIfExists("projects");

        $aclSeeder = new PermissionSeeder();
        $aclSeeder->deletePermissionType('Clientes');
        $aclSeeder->deletePermissionType('Times');
        $aclSeeder->deletePermissionType('Squads');
        $aclSeeder->deletePermissionType('Projectos');
    }
};
