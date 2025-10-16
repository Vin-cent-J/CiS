<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function salesReport($type, $date)
    {
        [$year, $month] = explode('-', $date);

        $reports = DB::table('sales_details as sd')
            ->join('products as p', 'sd.products_id', '=', 'p.id')
            ->join('sales as s', 's.id', '=', 'sd.sales_id')
            ->select(
                'p.name as product',
                DB::raw('MAX(sd.price) as unit_price'),
                DB::raw('SUM(sd.amount) as quantity'),
                DB::raw('SUM(sd.total_return) as total_return'),
                DB::raw('
                    SUM(
                        CASE 
                            WHEN sd.discount_type = 2 
                                THEN (sd.price * sd.amount * (sd.discount/100)) 
                            ELSE sd.discount * sd.amount
                        END
                    ) / SUM(sd.amount) as average_discount'),
                DB::raw("
                    SUM(
                        CASE 
                            WHEN sd.discount_type = 2 
                                THEN (sd.price * (1 - sd.discount/100)) * sd.amount
                            ELSE (sd.price - sd.discount) * sd.amount
                        END
                    ) as total
                "),
                DB::raw('
                    SUM(
                        case
                            when sd.discount_type = 2 
                                then (sd.price * sd.discount/100) * sd.amount
                            else sd.discount * sd.amount
                        end
                    ) as total_discount')
            )
            ->whereMonth('s.date', $month)
            ->whereYear('s.date', $year)
            ->groupBy('p.name')
            ->get();

        $discountTotal = Sale::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('discount');

        $month = substr($date, 5, 2);
        $year  = substr($date, 0, 4);

        $pdf = Pdf::loadView('report.sales', [
            'reports' => $reports,
            'date'   => $date,
            'type'   => $type,
            'month'  => $month,
            'year'   => $year,
            'discountTotal' => $discountTotal
        ]);

        return $pdf->download("sales-report-$date-$type.pdf");
    }
}
