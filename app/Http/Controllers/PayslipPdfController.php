<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PayslipPdfController extends Controller
{
    public function __invoke(Payslip $payslip)
    {
        $payslip->load('employee');

        $pdf = Pdf::loadView('pdf.payslip', [
            'payslip' => $payslip,
            'employee' => $payslip->employee,
        ]);

        return $pdf->download('fiche-paie-'.$payslip->employee->last_name.'-'.$payslip->month.'-'.$payslip->year.'.pdf');
    }
}
