<?php

namespace App\Http\Controllers;

use App\Exceptions\ResponseException;
use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Responses\SuccessResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

use function Tinify\fromBuffer;

class UserController extends Controller
{
    public function index(IndexUserRequest $request): SuccessResponse
    {
        $request->validated();

        $users = User::paginate($request->query('count'))->withQueryString();
        $users = new UserCollection($users);

        return new SuccessResponse(200, $users->toArray(request()));
    }

    public function store(StoreUserRequest $request): SuccessResponse
    {
        $validated = $request->validated();

        $photo = $request->file('photo');
        $path = 'storage/uploads/';
        $filename = time() . '_' . Str::random(10) . '.' . $photo->extension();

        $image = Image::read($photo);
        $image->crop(70, 70, position: 'center');

        $source = fromBuffer($image->encode());
        $source->toFile($path . $filename);

        $validated['photo'] = $path . $filename;

        $user = User::create($validated);

        $data = [
            'user_id' => $user->id,
            'message' => 'New user successfully registered'
        ];
        return new SuccessResponse(201, $data);
    }

    public function show(string $id): SuccessResponse
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'integer']
        );

        if ($validator->fails()) {
            $message = 'The user with the requested id does not exist';
            throw new ResponseException(400, $message, $validator->errors());
        }

        $user = User::find($id);

        if (!$user) {
            $message = 'User not found';
            throw new ResponseException(404, $message);
        }

        return new SuccessResponse(200, ['user' => new UserResource($user)]);
    }
}
