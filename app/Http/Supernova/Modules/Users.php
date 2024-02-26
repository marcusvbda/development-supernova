<?php

namespace App\Http\Supernova\Modules;

use App\Models\AccessGroup;
use App\Models\User;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FIELD_TYPES;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;
use marcusvbda\supernova\Panel;

class Users extends Module
{
    public function subMenu(): string
    {
        return "Time";
    }

    public function name(): array
    {
        return ['Usuário', 'Usuários'];
    }

    public function model(): string
    {
        return User::class;
    }

    public function dataTable(): array
    {
        $columns[] = Column::make("id", "Id")->width("200px")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::NUMBER_RANGE);
        $columns[] = Column::make("name", "Nome")
            ->searchable()->sortable()
            ->filterable(FILTER_TYPES::TEXT);
        $columns[] = Column::make("created_at", "Criado em ...")
            ->searchable()->sortable()
            ->callback(fn ($row) => $row->created_at?->format("d/m/Y - H:i:s"))
            ->filterable(FILTER_TYPES::DATE_RANGE);
        return $columns;
    }

    public function fields(): array
    {
        return [
            Panel::make("Informações")->fields([
                Field::make("name", "Nome")->rules(["required"]),
                Field::make("email", "Email")->rules(["email", "required"]),
                Field::make("linkedin", "URL do Linkedin")->rules(["url", "nullable"]),
                Field::make("whatsapp", "Whatsapp"),
                Field::make("position", "Cargo"),
            ]),
            Panel::make("Nivel de acesso")->fields([
                Field::make("access_group_id", "Grupo de Acesso")->rules(["required"])
                    ->type(FIELD_TYPES::SELECT)
                    ->options(AccessGroup::class)
            ]),
            Panel::make("Credenciais")->fields([
                Field::make("new_password", "Senha"),
                Field::make("password_confirmation", "Confirmação de Senha")->rules(["nullable", "same:new_password"])
            ])->canSee(true)
        ];
    }
}
