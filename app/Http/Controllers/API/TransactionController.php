<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TransactionRepository;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TransactionController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Repositories\TransactionRepository $repository
     */
    public function __construct(
        private TransactionRepository $repository
    ) {}

    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return TransactionResource::collection(
            $this->repository->filter($request)
            ->paginate($request->get('limit') ?? 20)
        );
    }
}
