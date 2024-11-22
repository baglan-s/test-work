<?php

namespace App\Http\Controllers\API;

use \Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ErrorResource;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    private $userRepository;

    /**
     *
     * @param UserRepository $userRepository
     */

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get list of users
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */

    public function index(Request $request): AnonymousResourceCollection
    {
        return UserResource::collection(
            $this->userRepository->model()->paginate($request->limit ?? 20)
        );
    }

    /**
     * Get user information by id.
     *
     * @param integer $id
     * @return JsonResource
     */
    public function show(int $id): JsonResource
    {
        if (!$user = $this->userRepository->getById($id)) {
            return new ErrorResource(new EntityNotFoundException());
        }

        return new UserResource($user);
    }

    /**
     * Get authenticated user information
     *
     * @return JsonResource
     */
    public function authenticated(): JsonResource
    {
        if (!$user = Auth::user()) {
            return new ErrorResource(new EntityNotFoundException());
        }

        return new UserResource($user);
    }

    /**
     * Create a new user
     *
     * @param UserCreateRequest $request
     * @return JsonResource
     */

    public function store(UserCreateRequest $request): JsonResource
    {
        try {
            return new UserResource(
                $this->userRepository->create($request->validated())
            );
        } catch (Exception $e) {
            return new ErrorResource($e);
        }
    }

    /**
     * UUpdate user
     *
     * @param UserUpdateRequest $request
     * @param integer $id
     * @return JsonResource
     */
    public function update(UserUpdateRequest $request, int $id): JsonResource
    {
        try {
            return new UserResource(
                $this->userRepository->update($id, $request->validated())
            );
        } catch (Exception $e) {
            return new ErrorResource($e);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userRepository->delete($id);

            return response()->json([]);
        } catch (Exception $e) {
            return new ErrorResource($e);
        }
    }
}
