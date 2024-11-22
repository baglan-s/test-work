<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\UserRepository;
use App\Jobs\BalanceAdd;

class UserBalanceAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:balance-add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add money to user balance';

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

            $amount = (float)$this->ask('Enter amount to add');

            // Если пользователь не найден, вывести ошибку
            if ($amount < 0) {
                $this->error('You can add only positive numbers.');
                
                return;
            }

            $description = $this->ask('Enter transaction description');

            // Добавление суммы к балансу
            BalanceAdd::dispatch(
                $user->wallet()
                    ->lockForUpdate()
                    ->first(),
                $amount,
                $description
            );

            $this->info('Money added successfully.');
        } catch (\Exception $e) {
            $this->error('An error occurred: '. $e->getMessage());
            return;
        }
    }
}
