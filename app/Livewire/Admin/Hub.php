<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Hub extends Component
{
    public string $tab = 'dashboard'; // dashboard | products | categories | brands | customers | orders

    // Permite abrir el hub en una pestaña concreta: /dashboard?tab=products o #products
    public function mount()
    {
        $fromQuery = request('tab');
        if ($fromQuery) $this->tab = $fromQuery;
    }

    public function setTab(string $tab)
    {
        $this->tab = $tab;
        // actualizar la URL sin recargar (opcional)
        $this->dispatch('push-url', tab: $tab);
    }

    public function render()
    {
        return view('livewire.admin.hub');
    }
}
