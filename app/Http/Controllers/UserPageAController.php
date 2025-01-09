<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPageAController extends Controller
{
    public function index(Request $request): View
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $user = User::where([
            'username' => $request->username,
            'phone_number' => $request->phoneNumber
        ])
            ->firstOrFail();

        $tmpLink = $user->userTmpLinks()->where('unique_id', $request->unique)->firstOrFail();

        if (! $tmpLink->is_active) {
            abort(401);
        }

        $request->session()->put('user_id', $user->id);

        $userTmpLinks = $user->userTmpLinks()->where('is_active', true)->get();

        return view('userPageA', compact('userTmpLinks'));
    }

    public function runRoll (User $user): JsonResponse
    {
        $number = rand(1, 1000);
        $rollResult = 'Lose';

        switch($number) {
            case $number % 2 != 0:
                $win = 0;
                break;
            case $number > 900:
                $win = $number * 0.7;
                break;
            case $number > 600:
                $win = $number * 0.5;
                break;
            case $number > 300:
                $win = $number * 0.3;
                break;
            case $number <= 300:
                $win = $number * 0.1;
                break;
            default:
                $win = 0;
        }

        if ($win > 0) {
            $rollResult = 'Win';
            $win = round($win, 2);
        }

        $user->userRollHistories()->create([
            'number' => $number,
            'win' => $win,
            'roll_result' => $rollResult
        ]);

        return response()->json([
            'number' => $number,
            'win' => $win,
            'rollResult' => $rollResult
        ]);
    }

    public function userHistory(User $user): JsonResponse
    {
        $historyCountToShow = 3;
        $rollHistory = $user->userRollHistories()->latest()->take($historyCountToShow)->get();

        return response()->json([
            'rollHistory' => $rollHistory
        ]);
    }
}
