<?php

namespace App\Http\Supernova\Modules;

use App\Models\AccessGroup;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use marcusvbda\supernova\Column;
use marcusvbda\supernova\Field;
use marcusvbda\supernova\FIELD_TYPES;
use marcusvbda\supernova\FILTER_TYPES;
use marcusvbda\supernova\Module;
use marcusvbda\supernova\Panel;
use marcusvbda\supernova\UPLOAD_PREVIEW;

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

    public function fields($row, $page): array
    {
        // $isRoot = @$row->role !== "root";
        $isRoot = false;
        $isCreateOrEdit = in_array($page, ["create", "edit"]);
        return [
            Panel::make("Informações")->fields([
                Field::make("avatar", "Avatar")->canSee(!$isRoot)
                    ->type(FIELD_TYPES::UPLOAD)
                    ->rules(["nullable", "image", "max:2048"])
                    ->preview(UPLOAD_PREVIEW::AVATAR),
                Field::make("name", "Nome")->rules(["required"]),
                Field::make("email", "Email")->rules([
                    // $isRoot ? "min:1"  : "email", 
                    "required"
                ]),
                Field::make("linkedin", "URL do Linkedin")->rules(["url", "nullable"])->canSee(!$isRoot),
                Field::make("whatsapp", "Whatsapp")->mask("(99) 99999-9999")->canSee(!$isRoot),
                Field::make("position", "Cargo")->canSee(!$isRoot)
            ]),
            Panel::make("Nivel de acesso")->fields([
                Field::make("access_group_id", "Grupo de Acesso")->rules(["required"])
                    ->type(FIELD_TYPES::SELECT, "access_group")
                    ->options(AccessGroup::class)
                    ->canSee(!$isRoot)
            ]),
            Panel::make("Credenciais")->fields([
                Field::make("new_password", "Senha")
                    ->type(FIELD_TYPES::PASSWORD)->rules(["nullable"]),
                Field::make("password_confirmation", "Confirmação de Senha")
                    ->type(FIELD_TYPES::PASSWORD)
                    ->rules(["nullable", "same:values.new_password"])
            ])->canSee($isCreateOrEdit)
        ];
    }

    public function makeModel($init = null): mixed
    {
        $query = app()->make($this->model());
        $user = Auth::user();
        if ($user->role === "root") {
            return $query;
        } else {
            return $query->where("role", "!=", "root");
        }
    }

    public function getCacheQtyKey(): string
    {
        $user = Auth::user();
        return 'qty:' . $this->id() . ':' . $user->role;
    }


    public function getCachedQty(): int
    {
        $cacheTime = 60 * 24;
        return cache()->remember($this->getCacheQtyKey(), $cacheTime, function () {
            return $this->makeModel()->count();
        });
    }

    public function canDeleteRow($row): bool
    {
        if ($row->role == "root") return false;
        return true;
    }

    public function onSave($id, $values, $info = []): int
    {
        $new_password = data_get($values, "save.new_password");
        $id = parent::onSave($id, $values, $info);
        if ($new_password) {
            $user = User::find($id);
            $user->password = bcrypt($new_password);
            $user->save();
        }

        return $id;
    }
}
