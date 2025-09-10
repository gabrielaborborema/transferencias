<div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold text-center text-gray-800">Crie sua Conta</h1>

    <form wire:submit="register" class="space-y-5">
        
        <x-input 
            wire:model="name" 
            label="Nome Completo" 
            placeholder="Seu nome completo" 
            icon="user"
            autofocus 
        />

        <x-input 
            wire:model="cpf" 
            label="CPF" 
            placeholder="000.000.000-00" 
            icon="identification"
            hint="Apenas números"
        />

        <x-input 
            wire:model="email" 
            label="Email" 
            placeholder="seu@email.com" 
            type="email"
            icon="at-symbol"
        />

        <x-password 
            wire:model="password" 
            label="Senha" 
            placeholder="Crie uma senha forte"
        />

        <x-password 
            wire:model="password_confirmation" 
            label="Confirmar Senha" 
            placeholder="Repita a senha"
        />

        <x-button 
            type="submit" 
            label="Registrar" 
            primary 
            full 
            spinner="register" 
        />
    </form>
    
    <div class="text-center">
        <a href="{{ route('login') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">
            Já tem uma conta? Faça login
        </a>
    </div>
</div>