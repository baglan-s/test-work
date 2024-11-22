<?php

namespace App\Repositories;

use App\Models\User;
use App\Exceptions\EntityNotFoundException;

class UserRepository
{
    public function model(): User
    {
        return new User();
    }

    public function getById(int $id): User | null
    {
        return User::find($id);
    }

    public function getByEmail(string $email): User | null
    {
        return User::where('email', $email)->first();
    }

    public function create(array $attributes): User
    {
        $user = User::create($attributes);

        return $user;
    }

    public function update(int $id, array $attributes): User
    {
        if (!$user = $this->getById($id)) {
            throw new EntityNotFoundException();
        }

        $user->update($attributes);

        return $this->getById($id);
    }

    public function delete(int $id)
    {
        if (!$user = $this->getById($id)) {
            throw new EntityNotFoundException();
        }

        $user->delete();
    }
}