<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

class PdfController extends Controller
{

    public function generatePDF()
    {
        // Load the Blade template
        $template = 'pdf-template.work-order';
        $dynamicData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'date' => date('Y-m-d'),
        ];

        $htmlContent = View::make($template, $dynamicData)->render();
        $dompdf = new Dompdf();

        $dompdf->loadHtml($htmlContent);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream('sample.pdf', ['Attachment' => false]);
    }

}
