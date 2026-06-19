<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Http\Requests\JoinGroupRequest;
use App\Models\Group;
use App\Service\GroupService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    use ApiResponseTrait;
    public function __construct(private GroupService $groupService)
    { }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->successResponse($this->groupService->myGroups($request->user()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupRequest $request)
    {
        $group = $this->groupService->create($request->validated(), $request->user());

        return $this->successResponse($group, 'Group created successfully.', 201);
    }

    public function join(JoinGroupRequest $request)
    {
        try {
            $group = $this->groupService->joinGroup($request->invite_code, $request->user());
            return $this->successResponse($group, 'Joined group successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        return $this->successResponse($this->groupService->show($group));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Group $group)
    {
        try {
            $this->groupService->delete($group, $request->user());
            return $this->successResponse(null, 'Group deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 403);
        }
    }
}
