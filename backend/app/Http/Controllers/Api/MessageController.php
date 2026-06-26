<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Models\Group;
use App\Models\Message;
use App\Service\MessageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ApiResponseTrait;
     public function __construct(private MessageService $messageService)
    { }
    public function index(Group $group, MessageRequest $request) {
        $message = $this->messageService->messagesByGroup($group, $request->user());
        return $this->successResponse($message);
    }

    public function store(MessageRequest $request, Group $group) {
        $message = $this->messageService->create($group, $request->validated(), $request->user());
        return $this->successResponse($message, 'Message sent successfully.', 201);
    }

    public function destroy(Request $request, Message $message) {
        $this->messageService->delete($message, $request->user());
        return $this->successResponse(null, 'Message deleted successfully.');
    }
}
