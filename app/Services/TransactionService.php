<?php

namespace App\Services;

use App\Jobs\NotifyEmail;
use App\Models\User;
use Brick\Math\BigDecimal;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function executeTransfer(User $sender, User $receiver, string $amount): void
    {
        $amountDecimal = BigDecimal::of($amount);
        $senderBalanceDecimal = BigDecimal::of($sender->balance);
        $receiverBalanceDecimal = BigDecimal::of($receiver->balance);

        if ($sender->id === $receiver->id) {
            throw new Exception("Não é possível enviar dinheiro para si mesmo");
        }

        if ($sender->type === 'store') {
            throw new Exception("Lojista não podem enviar dinheiro");
        }

        if ($senderBalanceDecimal->isLessThan($amountDecimal)) {
            throw new Exception("Saldo insuficiente");
        }

        $response = Http::get('https://util.devi.tools/api/v2/authorize');

        if ($response->failed()) {
            throw new Exception('Serviço de autorização indisponível');
        }

        $data = $response->json();

        if (empty($data['data']['authorization'])) {
            throw new Exception('Transferência não autorizada');
        }

        DB::transaction(function () use ($sender, $receiver, $senderBalanceDecimal, $receiverBalanceDecimal, $amountDecimal) {
            $newSenderBalance = $senderBalanceDecimal->minus($amountDecimal);
            $newReceiverBalance = $receiverBalanceDecimal->plus($amountDecimal);

            $sender->update(['balance' => $newSenderBalance]);
            $receiver->update(['balance' => $newReceiverBalance]);

            NotifyEmail::dispatch($sender, $receiver, (string) $amountDecimal);
        });
    }
}
