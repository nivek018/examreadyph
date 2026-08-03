<?php

namespace App\Http\Controllers;

use App\Services\AdPopupService;
use Illuminate\Http\Request;

class AdTrackingController extends Controller
{
    public function __construct(protected AdPopupService $adService) {}

    public function impression(Request $request, int $ad)
    {
        $this->adService->recordImpression($ad);
        return response()->json(['success' => true]);
    }

    public function click(Request $request, int $ad)
    {
        $this->adService->recordClick($ad);
        return response()->json(['success' => true]);
    }
}
