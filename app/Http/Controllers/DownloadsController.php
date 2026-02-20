<?php

namespace App\Http\Controllers;

use App\Services\DiscordDownloadsService;
use Illuminate\Contracts\View\View;

class DownloadsController extends Controller
{
    public function __construct(private readonly DiscordDownloadsService $downloadsService)
    {
    }

    public function index(): View
    {
        return view('downloads.index', [
            'files' => $this->downloadsService->getDownloadableFiles(),
        ]);
    }
}
