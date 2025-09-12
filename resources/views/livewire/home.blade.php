<div class="w-full max-w-4xl p-4 sm:p-8 space-y-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Olá, {{ auth()->user()->name }}!
            </h1>
            <p class="text-gray-600">Seja bem-vindo(a) de volta.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button
                type="submit"
                label="Sair"
                icon="arrow-left-on-rectangle"
                red
                flat />
        </form>
    </div>

    <div
        wire:poll.15s="updateBalance"
        class="p-6 bg-primary-500 rounded-lg text-white">
        <h2 class="text-lg font-semibold">Seu Saldo</h2>
        <p class="text-4xl font-bold">
            R$ {{ number_format($this->balance, 2, ',', '.') }}
        </p>
    </div>

    <div class="pt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Realizar Transferência</h2>

        @if (is_null($receiver))
        <div class="space-y-4">
            <x-input
                wire:model.lazy="transferKey"
                label="Para quem você quer transferir?"
                placeholder="Digite o Email ou CPF/CNPJ"
                icon="key"
                wire:keydown.enter="findReceiver" />
            <x-button wire:click="findReceiver" label="Continuar" primary full spinner="findReceiver" />
        </div>
        @else
        <div class="p-4 border rounded-lg bg-gray-50 mb-4">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Você está transferindo para:</p>
                    <p class="font-bold text-lg text-gray-800">{{ $receiver->name }}</p>
                    <p class="text-sm text-gray-500">CPF/CNPJ: {{ $receiver->cpf_cnpj }}</p>
                </div>
                <x-button wire:click="changeReceiver" label="Alterar" flat />
            </div>
        </div>

        <form wire:submit="transfer" class="space-y-4">
            <x-currency
                wire:model="amount"
                label="Valor"
                placeholder="0,00"
                prefix="R$"
                thousands="."
                decimal=","
                precision="2"
                icon="banknotes"
                autofocus />

            <x-button type="submit" label="Transferir" primary full spinner="transfer" />
        </form>
        @endif
    </div>
</div>
