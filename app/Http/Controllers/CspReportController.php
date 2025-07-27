<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

final class CspReportController extends Controller
{
    /**
     * Handle CSP violation reports.
     */
    public function report(Request $request): Response
    {
        $report = $request->json()->all();

        // Log the CSP violation
        Log::warning('CSP Violation Report', [
            'report' => $report,
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'timestamp' => now(),
        ]);

        // Optionally, you can store violations in database
        // or send them to an external monitoring service

        return response('', 204);
    }
}
