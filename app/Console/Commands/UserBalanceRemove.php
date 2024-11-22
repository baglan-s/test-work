<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\UserRepository;
use App\Jobs\BalanceRemove;

class UserBalanceRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:balance-remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove money from user balance';

    /**
     * Execute the console command.
     */
    public function handle(UserRepository $userRepository)
    {
        try {
            $email = $this->ask('Enter user EMAIL');

            // Если пользователь не найден, вывести ошибку
            if (!$user = $userRepository->getByEmail($email)) {
                $this->error('User not found.');
                
                return;
            }

            $amount = (float)$this->ask('Enter amount to remove');

            // Если пользователь не найден, вывести ошибку
            if ($amount < 0) {
                $this->error('You can remove only positive numbers');
                
                return;
            }

            // Если у пользователя недостаточно средств, вывести ошибку
            if ($user->wallet->balance < $amount) {
                $this->error('Not enough funds.');
                
                return;
            }

            $description = $this->ask('Enter transaction description');

            // Уменьшение суммы с баланса
            BalanceRemove::dispatch(
                $user->wallet()
                    ->lockForUpdate()
                    ->first(),
                $amount,
                $description
            );

            $this->info('Money removed successfully.');
        } catch (\Exception $e) {
            $this->error('An error occurred: '. $e->getMessage());
            return;
        }
    }
}
