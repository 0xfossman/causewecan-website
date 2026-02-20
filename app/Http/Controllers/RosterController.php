<?php

namespace App\Http\Controllers;

use App\Services\StormforgeService;
use Illuminate\Contracts\View\View;

class RosterController extends Controller
{
    public function __construct(private readonly StormforgeService $stormforgeService)
    {
    }

    public function index(): View
    {
        return view('roster.index', [
            'members' => $this->stormforgeService->getGuildRoster(),
        ]);
    }
}
