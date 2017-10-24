<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use Carbon\Carbon;
use Mail;

class DocumentController extends Controller
{
    //

    /*
     *
     * Json Example For This
     * {
          "nama_tertanggung" : "Kevin Murvie",
          "kategori_kendaraan": "non truck",
          "jenis_asuransi": "Otomate",
          "tahun_kendaraan": "2012",
          "agent_name" : "Rand Ferdyanto",
          "nilai_pertanggungan" : "150000000",
          "premi" : "7000000",
          "third_party" : "",
          "personal_accident" : "",
          "merek_kendaraan" : "Toyota",
          "user_email" : "ferdyantorand@gmail.com",
          "rate" : "4.80"
        }
    }*/
    public function SendDocument(){
        try {
            $json_result = file_get_contents('php://input');
            $json = json_decode($json_result);
            //$harga_kendaraan = number_format($json->harga_kendaraan, 0, ",", ".");
            $newFileName = $json->agent_name.Carbon::now('Asia/Jakarta')->format('Ymdhms');

            $path = public_path('document/');
            Excel::load($path . '/Surat Penawaran New otomate.xlsx', function($reader) use($json)
            {
                $reader->sheet('Otomate', function($sheet) use($json)
                {
                    //Set The field Data
                    $sheet->getCell('H9')->setValueExplicit('Tangerang, '.Carbon::now('Asia/Jakarta')->format('d M Y'));
                    $sheet->getCell('E21')->setValueExplicit($json->nama_tertanggung);
                    $sheet->getCell('E23')->setValueExplicit($json->merek_kendaraan);
                    $sheet->getCell('E25')->setValueExplicit($json->tahun_kendaraan);
                    $sheet->getCell('E29')->setValueExplicit('Rp'.number_format($json->nilai_pertanggungan, 0, ",", "."));
                    $sheet->getCell('G33')->setValueExplicit('Rp'.number_format($json->nilai_pertanggungan, 0, ",", "."));
                    $sheet->getCell('H33')->setValueExplicit('Rp'.number_format($json->premi, 0, ",", "."));
                    $sheet->getCell('G40')->setValueExplicit($json->third_party);
                    $sheet->getCell('F33')->setValueExplicit($json->rate.'%');
                    $sheet->getCell('G41')->setValueExplicit($json->personal_accident);
                    $sheet->getCell('H43')->setValueExplicit('Rp'.number_format($json->premi, 0, ",", "."));
                    $sheet->getCell('H45')->setValueExplicit('Rp'.number_format($json->premi, 0, ",", "."));
                    $sheet->getCell('A63')->setValueExplicit($json->agent_name);
                });
            })
                ->setFilename($newFileName)
                ->store('xls', public_path('user_docs/'));

            //Send the Email
            Mail::send('emails.offer', ['user' => '123'], function ($message) use($newFileName, $json){
                //
                $message->attach(public_path('user_docs/'.$newFileName.'.xls'));
                $message->from('randf77@gmail.com', 'Offering Letter By ICalculator');
                $message->to($json->user_email, $json->nama_tertanggung)->subject('Offering Letter!');
            });

            return response('Success Send Surat Penaawaran!', 200)
                ->header('Content-Type', 'text/plain');
        }
        catch (Exception $ex){
            //Utilities::ExceptionLog($ex);
            return response($ex, 200)
                ->header('Content-Type', 'text/plain');
        }
    }
}