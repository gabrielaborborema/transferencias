<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the login page successfully', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSeeLivewire('auth.login');
});

it('logins to the application successfully', function () {
    $user = User::factory()->create(['email' => 'email@email.com', 'password' => 'password']);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login')
        ->assertRedirect('/home');

    $this->assertAuthenticated();
});
