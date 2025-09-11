<?php

namespace App\Services;

use App\Models\User;
use Brick\Math\BigDecimal;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionService
{
  public function executeTransfer(User $sender, User $receiver, string $amount): void
  {
    $amountDecimal = BigDecimal::of($amount);
    $senderBalanceDecimal = BigDecimal::of($sender->balance);
    $receiverBalanceDecimal = BigDecimal::of($receiver->balance);

    if ($sender->id === $receiver->id) {
      throw new Exception("You can't transfer to yourself.");
    }

    if ($sender->type === 'store') {
      throw new Exception("You can't transfer from a store");
    }

    if ($senderBalanceDecimal->isLessThan($amountDecimal)) {
      throw new Exception("Insufficient balance");
    }

    DB::transaction(function () use ($sender, $receiver, $senderBalanceDecimal, $receiverBalanceDecimal, $amountDecimal) {
      $newSenderBalance = $senderBalanceDecimal->minus($amountDecimal);
      $newReceiverBalance = $receiverBalanceDecimal->plus($amountDecimal);

      $sender->update(['balance' => $newSenderBalance]);
      $receiver->update(['balance' => $newReceiverBalance]);
    });
  }
}
