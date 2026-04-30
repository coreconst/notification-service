<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Notifications\Contracts\NotificationGatewayInterface;
use App\Notifications\Data\NotificationSendData;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __construct(
       private readonly NotificationGatewayInterface $notificationGateway
    ){}

    public function send(NotificationRequest $request): JsonResponse
    {
        $data = NotificationSendData::fromArray($request->validated());

        $res = $this->notificationGateway->send($data);

        return response()->json($res->toArray());
    }
}
