<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;


class TransactionRepository
{
    public function filter(Request $request): Builder
    {
        return $request->user()
            ?->wallet
            ?->transactions()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->whereRaw("lower(description) LIKE '%". mb_strtolower($request->get('search')). "%'");
            })->orderBy('created_at', strtoupper($request->get('order_type') ?? 'asc'));
    }
}