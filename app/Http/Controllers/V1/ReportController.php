<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;

/**
 * Class ReportController
 * @package App\Http\Controllers\V1
 */
class ReportController extends Controller {

    /**
     * @var ReportService
     */
    public $reportService;

    /**
     * ReportController constructor.
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService){
        $this->reportService = $reportService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {

        $report = $this->reportService->getReport();

        return successResponse('Report successfully retrieved', $report);
    }
}
