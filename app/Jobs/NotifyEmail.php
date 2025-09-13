<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    public int $tries = 5;
    public int $maxExceptions = 3;
    public array $backoff = [10, 60, 300, 1800];

    public function __construct(
        public User $sender,
        public User $receiver,
        public string $amount
    ) {
        //
    }

    public function handle(): void
    {
        $url = 'https://util.devi.tools/api/v1/notify';

        $payload = [
            'origem' => $this->sender->email,
            'destino' => $this->receiver->email,
            'amount' => $this->amount,
            'message' => "Transferência rebecida de {$this->sender->name} no valor de {$this->amount}"
        ];

        $response = Http::timeout(15)->post($url, $payload);

        if (!$response->successful()) {
            Log::error('Falha ao enviar notificação por email', ['status' => $response->status(), 'body' => $response->body()]);
            $response->throw();
        }

        Log::info('Notificação de email enviada com sucesso');
    }
}
