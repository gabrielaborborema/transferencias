<div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold text-center text-gray-800">Login</h1>

    <form wire:submit="login" class="space-y-6">

        <x-input
            wire:model="email"
            label="Email"
            placeholder="seu@email.com"
            icon="at-symbol"
            autofocus />

        <x-password
            wire:model="password"
            label="Senha"
            placeholder="Sua senha" />

        <x-checkbox
            id="remember"
            wire:model="remember"
            label="Lembrar-me" />

        <x-button
            type="submit"
            label="Entrar"
            primary full
            spinner="login" />
    </form>

    <div class="text-center">
        <a href="{{ route('register') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">
            Não tem uma conta? Crie uma agora
        </a>
    </div>
</div>