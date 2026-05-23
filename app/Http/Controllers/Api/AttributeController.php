<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeResource;
use App\Services\AttributeService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(
        protected AttributeService $attributeService
    ) {}

    public function index(Request $request)
    {
        $attributes = $this->attributeService->all();
        return AttributeResource::collection($attributes);
    }
}
