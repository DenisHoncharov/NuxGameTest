<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserTmpLinks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\URL;

class TemporaryLinkController extends Controller
{
    public function index(): View
    {
        return view('register');
    }

    public function register(Request $request): View
    {
        $userData = $request->validate([
            'username' => 'required|unique:users',
            'phone_number' => 'required|unique:users',
        ]);

        $unique = Str::random(5);
        $generatedLink = $this->generateTemporaryUrl($userData['username'], $userData['phone_number'], $unique);

        $user = User::create([
            'username' => $userData['username'],
            'phone_number' => $userData['phone_number']
        ]);

        $user->userTmpLinks()->create([
            'link' => $generatedLink,
            'unique_id' => $unique
        ]);

        return view('userLink', compact('generatedLink'));
    }

    public function regenerateLink(User $user): View
    {
        $unique = Str::random(5);
        $generatedLink = $this->generateTemporaryUrl($user->username, $user->phone_number, $unique);

        $user->userTmpLinks()->create([
            'link' => $generatedLink,
            'unique_id' => $unique
        ]);

        return view('userLink', compact('generatedLink'));
    }

    public function deactivateTmpLink(User $user, UserTmpLinks $userTmpLink): JsonResponse
    {
        $userTmpLink->is_active = false;
        $userTmpLink->save();

        $userTmpLinks = $user->userTmpLinks()->where('is_active', true)->get();

        return response()->json([
            'userTmpLinks' => $userTmpLinks
        ]);
    }

    private function generateTemporaryUrl(string $username, string $phoneNumber, string $unique): string
    {
        $linkLifeTime = now()->addDays(7);

        return URL::temporarySignedRoute(
            'pageA',
            $linkLifeTime,
            [
                'username' => $username,
                'phoneNumber' => $phoneNumber,
                'unique' => $unique
            ]
        );
    }
}
