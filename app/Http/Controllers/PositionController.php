<?php

namespace App\Http\Controllers;

use App\Http\Resources\PositionResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Position;

class PositionController extends Controller
{
    public function index(): SuccessResponse
    {
        $positions = PositionResource::collection(Position::all());

        return new SuccessResponse(200, ['positions' => $positions]);
    }
}
