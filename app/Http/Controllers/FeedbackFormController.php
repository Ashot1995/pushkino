<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackFormRequest;
use App\Models\FeedbackForm;
use App\Models\User;
use App\Notifications\SendFeedbackFormNotification;
use Illuminate\Support\Facades\Notification;

class FeedbackFormController extends Controller
{
    public function store(FeedbackFormRequest $request)
    {
        $validatedData = $request->validated();
        $feedbackForm = FeedbackForm::create([
            ...$validatedData,
            'file' => $request->has('file') ? $request->file('file')->storePublicly('/', ['disk' => 'public']) : null,
        ]);

        $user = new User();
        $user->email = config('notifications.main_address');
        Notification::send($user, new SendFeedbackFormNotification($feedbackForm));

        return response()->json($feedbackForm);
    }
}
