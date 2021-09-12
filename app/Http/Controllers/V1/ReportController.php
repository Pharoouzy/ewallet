<?php

namespace App\Http\Controllers\V1;

use App\Services\ReportService;

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
