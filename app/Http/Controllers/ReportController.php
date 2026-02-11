<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\AssetUnit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->buildReportData($request);
        return view('reports.index', $data);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->buildReportData($request);
        return Excel::download(new ReportExport($data), 'laporan-aset.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $data = $this->buildReportData($request);
        $pdf = Pdf::loadView('reports.pdf', $data);
        return $pdf->download('laporan-aset.pdf');
    }

    private function buildReportData(?Request $request = null): array
    {
        $dateFrom = $request?->input('date_from');
        $dateTo = $request?->input('date_to');

        $totalAssetTypes = Asset::count();
        $totalUnits = AssetUnit::count();
        $totalAssetValue = Asset::sum(\DB::raw('price * quantity'));

        $borrowedQuery = AssetLoan::where('status', 'borrowed');
        $returnedQuery = AssetLoan::where('status', 'returned');

        if ($dateFrom) {
            $borrowedQuery->whereDate('loan_date', '>=', $dateFrom);
            $returnedQuery->whereDate('return_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $borrowedQuery->whereDate('loan_date', '<=', $dateTo);
            $returnedQuery->whereDate('return_date', '<=', $dateTo);
        }

        $borrowedUnits = $borrowedQuery->sum('quantity_borrowed');
        $returnedUnits = $returnedQuery->sum(\DB::raw('COALESCE(original_quantity, quantity_borrowed)'));

        $unitStatusCounts = AssetUnit::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $assetStatusCounts = Asset::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $notUsableStatuses = ['maintenance', 'retired'];
        $notUsableUnits = AssetUnit::whereIn('asset_units.status', $notUsableStatuses)->count();
        $notUsableValue = AssetUnit::whereIn('asset_units.status', $notUsableStatuses)
            ->join('assets', 'assets.id', '=', 'asset_units.asset_id')
            ->sum(\DB::raw('assets.price'));

        $borrowedLoans = AssetLoan::with(['asset', 'user'])
            ->where('status', 'borrowed')
            ->when($dateFrom, fn ($q) => $q->whereDate('loan_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('loan_date', '<=', $dateTo))
            ->latest('loan_date')
            ->get();

        $returnedLoans = AssetLoan::with(['asset', 'user'])
            ->where('status', 'returned')
            ->when($dateFrom, fn ($q) => $q->whereDate('return_date', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('return_date', '<=', $dateTo))
            ->latest('return_date')
            ->get();

        $periodLabel = 'Semua Periode';
        if ($dateFrom && $dateTo) {
            $periodLabel = $dateFrom . ' s/d ' . $dateTo;
        } elseif ($dateFrom) {
            $periodLabel = 'Mulai ' . $dateFrom;
        } elseif ($dateTo) {
            $periodLabel = 'Sampai ' . $dateTo;
        }

        return [
            'totalAssetTypes' => $totalAssetTypes,
            'totalUnits' => $totalUnits,
            'totalAssetValue' => $totalAssetValue,
            'borrowedUnits' => $borrowedUnits,
            'returnedUnits' => $returnedUnits,
            'unitStatusCounts' => $unitStatusCounts,
            'assetStatusCounts' => $assetStatusCounts,
            'notUsableUnits' => $notUsableUnits,
            'notUsableValue' => $notUsableValue,
            'notUsableStatuses' => $notUsableStatuses,
            'borrowedLoans' => $borrowedLoans,
            'returnedLoans' => $returnedLoans,
            'filterDateFrom' => $dateFrom,
            'filterDateTo' => $dateTo,
            'periodLabel' => $periodLabel,
        ];
    }
}
