<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DocumentController extends Controller
{
    //
    public function SendDocument(){
        $path = public_path('document/');
        Excel::load($path . '/Surat Penawaran New otomate.xlsx', function($reader)
        {
            $reader->sheet('Otomate', function($sheet)
            {
                //Set The Name
                $sheet->getCell('E21')->setValueExplicit("Rand Ferdyanto");
            });
        })
        ->setFilename('test1')
        ->store('xls', public_path('user_docs/'));
    }
}