<?php

namespace Tests\Feature;

use Livewire\Livewire;
use Tests\TestCase;

class EcosystemFooterTest extends TestCase
{
    public function test_footer_lists_every_kisara_service(): void
    {
        Livewire::test(\App\Livewire\Dashboard\Section\Footer::class)
            ->assertSee('Kisara Mail')
            ->assertSee('Akun Kisara')
            ->assertSee('Kisara Workspace')
            ->assertSee('Kisara PDF');
    }
}
