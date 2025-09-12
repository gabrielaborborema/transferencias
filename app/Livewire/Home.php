<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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

  #[Rule('required|exists:users,id')]
  public ?int $receiverId = null;

  public function transfer(TransactionService $transactionService): void
  {
    $this->validate();

    $sender = Auth::user();

    $receiver = User::find($this->receiverId);

    try {
      $transactionService->executeTransfer($sender, $receiver, $this->amount);

      $this->notification()->success(
        'Transferência realizada!',
        'O valor foi enviado com sucesso.'
      );

      $this->reset('amount', 'receiverId');
      $this->dispatch('transfer-completed');
    } catch (\Exception $e) {
      $this->notification()->error(
        'Erro na transferência',
        $e->getMessage()
      );
    }
  }

  #[Computed(persist: true)]
  public function balance(): string
  {
    return Auth::user()->balance;
  }

  #[Computed]
  public function users(): Collection
  {
    return User::where('id', '!=', Auth::id())->get();
  }

  public function render()
  {
    return view('livewire.home');
  }
}
