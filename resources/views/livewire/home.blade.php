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
    wire:key="user-balance"
    wire:poll.15s
    wire:on:transfer-completed.window="$wire.balance(true)"
    class="p-6 bg-primary-500 rounded-lg text-white">
    <h2 class="text-lg font-semibold">Seu Saldo</h2>
    <p class="text-4xl font-bold">
      R$ {{ number_format($this->balance, 2, ',', '.') }}
    </p>
  </div>

  <div class="pt-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Realizar Transferência</h2>
    <form wire:submit="transfer" class="space-y-4">
      <x-select
        wire:model.live="receiverId"
        label="Para quem você quer transferir?"
        placeholder="Selecione um usuário"
        :options="$this->users"
        option-label="name"
        option-value="id"
        :clearable="false" />

      <x-currency
        wire:model="amount"
        label="Valor"
        placeholder="0,00"
        prefix="R$"
        thousands="."
        decimal=","
        icon="banknotes" />

      <x-button type="submit" label="Transferir" primary full spinner="transfer" />
    </form>
  </div>
</div>