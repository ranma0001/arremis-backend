<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use PDF;

class PdfController extends Controller
{

    public function generatePDF()
    {
        $logo  = public_path() . '/images/da.jpg';
        view()->share('logo', $logo);

        $pdf = PDF::loadView('pdf-template.work-order');
        $pdf->setPaper('letter', 'portrait');
        return $pdf->download('work-order.pdf');
    }

}
