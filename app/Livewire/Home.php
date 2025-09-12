<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('components.layouts.app')]
class Home extends Component
{
    use WireUiActions;

    #[Rule('required|numeric|gt:0')]
    public string $amount = '';

    #[Rule('required|string')]
    public string $transferKey = '';

    public ?User $receiver = null;

    public function findReceiver(): void
    {
        $this->validateOnly('transferKey');

        $cleanedTransferKey = preg_replace('/[^0-9]/', '', $this->transferKey);

        $query = User::query()
            ->where('id', '!=', Auth::id())
            ->where(function ($query) use ($cleanedTransferKey) {
                $query->where('email', $this->transferKey)
                    ->orWhere('cpf_cnpj', $this->transferKey)
                    ->orWhere('cpf_cnpj', $cleanedTransferKey);
            });

        $user = $query->first();

        if (!$user) {
            $this->notification()->error('Chave não encontrada', 'Nenhum usuário localizado para a chave informada.');
            $this->receiver = null;
            return;
        }

        $this->receiver = $user;
    }

    public function changeReceiver(): void
    {
        $this->reset('transferKey', 'receiver', 'amount');
    }

    public function transfer(TransactionService $transactionService): void
    {
        $this->validateOnly('amount');

        $sender = Auth::user();

        try {
            $transactionService->executeTransfer($sender, $this->receiver, $this->amount);

            $this->notification()->success(
                'Transferência realizada!',
                'O valor foi enviado com sucesso.'
            );

            $this->reset('amount', 'transferKey', 'receiver');
            $this->dispatch('transfer-completed');
        } catch (\Exception $e) {
            $this->notification()->error(
                'Erro na transferência',
                $e->getMessage()
            );
        }
    }

    #[On('transfer-completed')]
    public function updateBalance(): void
    {
        unset($this->balance);
    }

    #[Computed(persist: true)]
    public function balance(): string
    {
        return Auth::user()->balance;
    }

    public function render()
    {
        return view('livewire.home');
    }
}
