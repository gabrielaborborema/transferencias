<?php

namespace Tests\Feature\Services;

use App\Jobs\NotifyEmail;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Exception;

uses(RefreshDatabase::class);

it('successfully transfers money between users', function () {

    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response([
            'status' => 'success',
            'data' => [
                'authorization' => 'true'
            ]
        ], 200)
    ]);

    Queue::fake();

    $sender = User::factory()->create(['balance' => 1000, 'type' => 'common']);
    $receiver = User::factory()->create(['balance' => 30]);
    $amount = '200';

    $service = new TransactionService();
    $service->executeTransfer($sender, $receiver, $amount);

    expect($sender->refresh()->getRawOriginal('balance'))->toBe('800.00');
    expect($receiver->refresh()->getRawOriginal('balance'))->toBe('230.00');

    Queue::assertPushed(NotifyEmail::class);
});

it('throws an exception for sending money to yourself', function () {

    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response([
            'status' => 'success',
            'data' => [
                'authorization' => 'true'
            ]
        ], 200)
    ]);

    $sender = User::factory()->create(['balance' => 1000, 'type' => 'common']);
    $amount = '200';

    $service = new TransactionService();
    expect(fn() => $service->executeTransfer($sender, $sender, $amount))
        ->toThrow(Exception::class, 'Não é possível enviar dinheiro para si mesmo');
});

it('throws an exception for insufficient money', function () {

    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response([
            'status' => 'success',
            'data' => [
                'authorization' => 'true'
            ]
        ], 200)
    ]);

    $sender = User::factory()->create(['balance' => 1000, 'type' => 'common']);
    $receiver = User::factory()->create(['balance' => 0]);
    $amount = '2000';

    $service = new TransactionService();
    expect(fn() => $service->executeTransfer($sender, $receiver, $amount))
        ->toThrow(Exception::class, 'Saldo insuficiente');
});

it('throws an exception for sending money from a store', function () {

    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response([
            'status' => 'success',
            'data' => [
                'authorization' => 'true'
            ]
        ], 200)
    ]);

    $sender = User::factory()->create(['balance' => 1000, 'type' => 'store']);
    $receiver = User::factory()->create(['balance' => 0]);
    $amount = '300';

    $service = new TransactionService();
    expect(fn() => $service->executeTransfer($sender, $receiver, $amount))
        ->toThrow(Exception::class, 'Lojista não podem enviar dinheiro');
});

it('throws an exception for failed authorization', function () {

    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response([
            'status' => 'fail',
            'data' => [
                'authorization' => 'false'
            ]
        ], 403)
    ]);

    $sender = User::factory()->create(['balance' => 1000, 'type' => 'common']);
    $receiver = User::factory()->create(['balance' => 0]);
    $amount = '200';

    $service = new TransactionService();
    expect(fn() => $service->executeTransfer($sender, $receiver, $amount))
        ->toThrow(Exception::class, 'Transação não autorizada');
});

it('rolls back the transaction on failure', function () {

    Http::fake([
        'util.devi.tools/api/v2/authorize' => Http::response([
            'status' => 'success',
            'data' => [
                'authorization' => 'true'
            ]
        ], 200)
    ]);

    $sender = User::factory()->create(['balance' => '1000.00', 'type' => 'common']);
    $initialSenderBalance = $sender->getRawOriginal('balance');

    $receiver = \Mockery::spy(User::factory()->create(['balance' => 0]));
    $receiver->shouldReceive('update')->once()->andThrow(new Exception('Simulated database failure'));

    $amount = '200';

    $service = new TransactionService();

    try {
        $service->executeTransfer($sender, $receiver, $amount);
    } catch (Exception $e) {
        expect($e->getMessage())->toBe('Simulated database failure');
    }

    expect($sender->refresh()->getRawOriginal('balance'))->toBe($initialSenderBalance);
});
