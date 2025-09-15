<?php

use App\Livewire\Auth\Register;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the registration page successfully', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSeeLivewire('auth.register');
});

it('registers a new user with valid data successfuly', function () {
    Livewire::test(Register::class)
        ->set('name', 'Teste')
        ->set('cpf_cnpj', '27764236080')
        ->set('type', 'common')
        ->set('email', 'email@email.com')
        ->set('password', '123456')
        ->set('password_confirmation', '123456')
        ->call('register')
        ->assertRedirect('/home');

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'email' => 'email@email.com',
        'cpf_cnpj' => '27764236080'
    ]);
});

it('shows cnpj/cpf field dynamically', function () {
    Livewire::test(Register::class)
        ->assertSee('CPF')
        ->assertDontSee('CNPJ')
        ->set('type', 'store')
        ->assertSee('CNPJ')
        ->assertDontSee('CPF');
});