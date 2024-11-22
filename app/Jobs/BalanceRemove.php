<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Wallet;
use App\Models\Enums\TransactionType;
use Illuminate\Support\Facades\DB;

class BalanceRemove implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Wallet $wallet,
        private float $amount,
        private string|null $description = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            $this->wallet->decrement('balance', $this->amount);
            $this->wallet->transactions()->create([
                'type' => TransactionType::EXPENSE,
                'amount' => $this->amount,
                'description' => $this->description,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
