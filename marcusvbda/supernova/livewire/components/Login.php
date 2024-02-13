<?php

namespace marcusvbda\supernova\livewire\components;

use App\Http\Supernova\Application;
use Livewire\Component;

class Login extends Component
{
    public $logo;
    public $email;
    public $password;
    public $redirect;

    public function getRules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function mount()
    {
        $application = app()->make(config("supernova.application", Application::class));
        $this->logo = $application->logo();
    }

    public function submit()
    {
        $this->validate();

        $this->redirect($this->redirect ?? route("supernova.dashboard"));
    }

    public function render()
    {
        return view('supernova-livewire-views::login');
    }
}
