<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillRequest;
use App\Models\Bill;
use App\Models\Group;
use App\Service\BillService;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class BillController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private BillService $billService)
    { }

    public function index(Group $group)
    {
        // $bills = $group->bills()->with('creator')->get();
        $bills = $this->billService->billsByGroup($group, request()->user());

        return $this->successResponse($bills, 'Bills retrieved successfully.');
    }

    public function store(BillRequest $request, Group $group)
    {
        $bill = $this->billService->create($group, $request->validated(), $request->user());

        return $this->successResponse($bill, 'Bill created successfully.', 201);
    }

    public function show(Request $request, Bill $bill)
    {
       $billData = $this->billService->show($bill, $request->user());
       return $this->successResponse($billData, 'Bill details retrieved successfully.');
    }

    public function destroy(Request $request, Bill $bill)
    {
        $this->billService->delete($bill, $request->user());
        return $this->successResponse(null, 'Bill deleted successfully.');
    }
}
