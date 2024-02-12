<?php

namespace marcusvbda\supernova\livewire\components;

use Livewire\Component;

class Navbar extends Component
{
    public $config;

    public function mount()
    {
        $this->config = [
            'items' => $this->makeItems()
        ];
    }

    private function makeItems()
    {
        $items = [
            [
                'label' => 'Home',
                'url' => '/',
                'icon' => 'home'
            ],
            [
                'label' => 'About',
                'url' => '/about',
                'icon' => 'info'
            ],
            [
                'label' => 'Contact',
                'url' => '/contact',
                'icon' => 'phone'
            ]
        ];
        return $items;
    }

    public function render()
    {
        return view('supernova-livewire-views::navbar');
    }
}
