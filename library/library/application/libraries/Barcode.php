<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barcode
{

    public function generate($text, $filename, $folderpath = 'uploads/idcard/')
    {
        $barcode          = new Picqer\Barcode\BarcodeGeneratorJPG();
        $generate_barcode = $barcode->getBarcode($text, $barcode::TYPE_CODE_128);
        $newfilename      = FCPATH . $folderpath . $filename . '.jpg';
        if (!file_exists($newfilename)) {
            return file_put_contents($folderpath . $filename . '.jpg', $generate_barcode);
        } else {
            return false;
        }
    }
}
