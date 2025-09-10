<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Auth;
use WireUi\Traits\WireUiActions;


#[Layout('components.layouts.app')]
class Login extends Component
{
    use WireUiActions;

    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required')]
    public string $password = '';

    #[Rule('boolean')]
    public bool $remember = false;

    public function login()
    {
        $validated = $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {

            session()->regenerate();

            return $this->redirect(route('home'), navigate: true);
        }

        $this->notification()->error(
            $title = 'Authentication Failed',
            $description = 'Invalid credentials.'
        );
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
