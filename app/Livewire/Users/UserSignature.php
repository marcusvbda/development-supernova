<?php

namespace App\Livewire\Users;

use Livewire\Component;

class UserSignature extends Component
{
    private $values, $entity, $page;

    public function __construct($entity, $values, $page)
    {
        $this->values = $values;
        $this->entity = $entity;
        $this->page = $page;
    }

    public function render()
    {
        $avatarUrl = null;
        $values = $this->page === "details" ? $this->entity : $this->values;
        $avatar = data_get($values, "avatar.0");
        if ($avatar) {
            if (is_array($avatar)) {
                $id = data_get($avatar, "id");
                $path = data_get($avatar, "path");
                $disk = data_get($avatar, "disk");
                $name = data_get($avatar, "original_name");
                $extension = data_get($avatar, "extension");
                $path = str_replace("/", "-", $path);
                $fileName = $path . "-" . $id . "." . $extension;
                $avatarUrl = route("supernova.modules.upload-download", ["disk" => $disk, "file" => $fileName]);
            } else {
                $avatarUrl = $avatar->temporaryUrl();
            }
        }
        $name = data_get($values, "name");
        $position = data_get($values, "position");
        $email = data_get($values, "email");
        $phone = "11 3042-8452";
        $linkedin = data_get($values, "linkedin");
        $instagram = data_get($values, "instagram");
        $whatsapp = data_get($values, "whatsapp");
        return view('livewire.users.signature', compact('name', 'position', 'email', 'phone', 'linkedin', 'instagram', 'whatsapp', 'avatarUrl'));
    }
}
