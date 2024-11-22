<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\WalletResource;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Exceptions\EntityNotFoundException;

class WalletController extends Controller
{
    public function show(): JsonResource
    {
        if (!$wallet = Auth::user()?->wallet) {
            return new ErrorResource(
                new EntityNotFoundException('Wallet not found!')
            );
        }

        return new WalletResource($wallet);
    }
}
