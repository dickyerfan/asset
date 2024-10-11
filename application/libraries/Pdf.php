<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
    /**
     * PDF filename
     * @var String
     */
    public $filename;
    public $paper = 'folio';
    public $orientation = 'portrait';

    public function __construct()
    {
        $this->filename = "laporan.pdf";
    }

    /**
     * Set the paper size and orientation
     *
     * @param string $paper
     * @param string $orientation
     */
    public function setPaper($paper = 'folio', $orientation = 'portrait')
    {
        $this->paper = $paper;
        $this->orientation = $orientation;
    }

    /**
     * Load a CodeIgniter view into Dompdf and generate PDF
     *
     * @param string $view The view to load
     * @param array $data The view data
     * @return void
     */
    public function generate($view, $data = array())
    {
        $options = new Options();
        // $options->set('defaultFont', 'courier');
        $options->set('isRemoteEnabled', TRUE);
        // // Konfigurasi margin
        // $options->set('isPhpEnabled', true);
        // $options->set('isHtml5ParserEnabled', true);
        // $options->set('isFontSubsettingEnabled', true);
        // $options->set('isCssFloatEnabled', true);
        // $options->set('margin-top', '100mm');    // Atur margin atas
        // $options->set('margin-right', '10mm');  // Atur margin kanan
        // $options->set('margin-bottom', '5mm'); // Atur margin bawah
        // $options->set('margin-left', '10mm');   // Atur margin kiri

        $dompdf = new Dompdf($options);

        $ci = &get_instance(); // Mendapatkan instance CodeIgniter

        $html = $ci->load->view($view, $data, TRUE);

        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper($this->paper, $this->orientation);

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF to the browser
        $dompdf->stream($this->filename, array("Attachment" => false));
    }
}
