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
          "rate" : "4.80",
          "TSFWD_rate" : "0.000",
          "EQVET_rate" : "0.000",
          "SRCCTS_rate" : "0.000",
          "personal_accident_driver_rate" : "0.000",
          "personal_accident_penumpang_4_orang_rate" : "0.000",
          "third_party_rate" : "0.000",
          "TSFWD_tsi" : "0.000",
          "EQVET_tsi" : "0.000",
          "SRCCTS_tsi" : "0.000",
          "personal_accident_driver_tsi" : "0.000",
          "personal_accident_penumpang_4_orang_tsi" : "0.000",
          "third_party_tsi" : "0.000",
          "TSFWD_premi" : "0.000",
          "EQVET_premi" : "0.000",
          "SRCCTS_premi" : "0.000",
          "personal_accident_driver_premi" : "0.000",
          "personal_accident_penumpang_4_orang_premi" : "0.000",
          "third_party_premi" : "0.000",
        }
    }*/

    // Send Otomate & Otomate Smart
    public function SendDocument(){
        try {
            $json_result = file_get_contents('php://input');
            $json = json_decode($json_result);
            //$harga_kendaraan = number_format($json->harga_kendaraan, 0, ",", ".");
            $newFileName = $json->agent_name.Carbon::now('Asia/Jakarta')->format('Ymdhms');

            if($json->type == 'otomate'){
                $filePath = '/Surat Penawaran New otomate.xlsx';
            }
            else{
                $filePath = '/Surat Penawaran New otomate - Smart.xlsx';
            }

            $path = public_path('document/');
            Excel::load($path . $filePath, function($reader) use($json)
            {
                $reader->sheet('Otomate', function($sheet) use($json)
                {
                    //Set The field Data
                    $sheet->getCell('A10')->setValueExplicit($json->nama_tertanggung);
                    $sheet->getCell('H9')->setValueExplicit('Tangerang, '.Carbon::now('Asia/Jakarta')->format('d M Y'));
                    $sheet->getCell('E21')->setValueExplicit($json->nama_tertanggung);
                    $sheet->getCell('E23')->setValueExplicit($json->jenis_kendaraan);
                    $sheet->getCell('E25')->setValueExplicit($json->tahun_kendaraan);
                    $sheet->getCell('E29')->setValueExplicit('Rp'.number_format($json->nilai_pertanggungan, 0, ",", "."));
                    $sheet->getCell('F33')->setValueExplicit($json->rate.'%');
                    $sheet->getCell('G33')->setValueExplicit('Rp'.number_format($json->nilai_pertanggungan, 0, ",", "."));
                    $sheet->getCell('H33')->setValueExplicit('Rp'.number_format($json->premi, 0, ",", "."));
                    $sheet->getCell('G40')->setValueExplicit($json->third_party);
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

    // Function to send Otomate Solitare Document
    public function SendSolitareDocument(){
        try {
            $json_result = file_get_contents('php://input');
            $json = json_decode($json_result);
            //$harga_kendaraan = number_format($json->harga_kendaraan, 0, ",", ".");
            $newFileName = $json->agent_name.Carbon::now('Asia/Jakarta')->format('Ymdhms');

            $path = public_path('document/');
            Excel::load($path . '/Surat Penawaran New otomate - Solitare.xlsx', function($reader) use($json)
            {
                $reader->sheet('Solitare', function($sheet) use($json)
                {
                    //Set The field Data
                    $sheet->getCell('A10')->setValueExplicit($json->nama_tertanggung);
                    $sheet->getCell('H9')->setValueExplicit('Tangerang, '.Carbon::now('Asia/Jakarta')->format('d M Y'));
                    $sheet->getCell('E21')->setValueExplicit($json->nama_tertanggung);
                    $sheet->getCell('E23')->setValueExplicit($json->jenis_kendaraan);
                    $sheet->getCell('E25')->setValueExplicit($json->tahun_kendaraan);
                    $sheet->getCell('E29')->setValueExplicit('Rp'.number_format($json->nilai_pertanggungan, 0, ",", "."));
                    $sheet->getCell('G33')->setValueExplicit('Rp'.number_format($json->nilai_pertanggungan, 0, ",", "."));
                    $sheet->getCell('H33')->setValueExplicit('Rp'.number_format($json->premi, 0, ",", "."));
                    $sheet->getCell('G41')->setValueExplicit($json->third_party);
                    $sheet->getCell('F33')->setValueExplicit($json->rate.'%');
                    $sheet->getCell('G42')->setValueExplicit($json->personal_accident);
                    $sheet->getCell('H44')->setValueExplicit('Rp'.number_format($json->premi, 0, ",", "."));
                    $sheet->getCell('H46')->setValueExplicit('Rp'.number_format($json->premi, 0, ",", "."));
                    $sheet->getCell('A64')->setValueExplicit($json->agent_name);
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

    // Function to Send Comprehensive Document
    public function SendComprehensiveDocument(){
        try {
            $json_result = file_get_contents('php://input');
            $json = json_decode($json_result);
            //$harga_kendaraan = number_format($json->harga_kendaraan, 0, ",", ".");
            $newFileName = $json->agent_name.Carbon::now('Asia/Jakarta')->format('Ymdhms');

            if($json->type == 'otomate'){
                $filePath = '/Surat Penawaran New otomate.xlsx';
            }
            else{
                $filePath = '/Surat Penawaran New otomate - Smart.xlsx';
            }

            $path = public_path('document/');
            Excel::load($path . $filePath, function($reader) use($json)
            {
                $reader->sheet('Comprehensive', function($sheet) use($json)
                {
                    //Set The field Data
                    $sheet->getCell('A10')->setValueExplicit($json->nama_tertanggung);
                    $sheet->getCell('H9')->setValueExplicit('Tangerang, '.Carbon::now('Asia/Jakarta')->format('d M Y'));
                    $sheet->getCell('E21')->setValueExplicit($json->nama_tertanggung);
                    $sheet->getCell('E23')->setValueExplicit($json->jenis_kendaraan);
                    $sheet->getCell('E25')->setValueExplicit($json->tahun_kendaraan);
                    $sheet->getCell('E29')->setValueExplicit('Rp'.number_format($json->nilai_pertanggungan, 0, ",", "."));

                    // Table Field
                    $sheet->getCell('G33')->setValueExplicit('Rp'.number_format($json->nilai_pertanggungan, 0, ",", "."));
                    $sheet->getCell('H33')->setValueExplicit('Rp'.number_format($json->premi, 0, ",", "."));
                    $sheet->getCell('F33')->setValueExplicit($json->rate.'%');

                    //Field Pertambahan
                    $sheet->getCell('F36')->setValueExplicit($json->TSFWD_rate);
                    $sheet->getCell('G36')->setValueExplicit($json->TSFWD_tsi);
                    $sheet->getCell('H36')->setValueExplicit('Rp'.number_format($json->TSFWD_premi, 0, ",", "."));

                    $sheet->getCell('F37')->setValueExplicit($json->EQVET_rate);
                    $sheet->getCell('G37')->setValueExplicit($json->EQVET_tsi);
                    $sheet->getCell('H37')->setValueExplicit('Rp'.number_format($json->EQVET_premi, 0, ",", "."));

                    $sheet->getCell('F38')->setValueExplicit($json->SRCCTS_rate);
                    $sheet->getCell('G38')->setValueExplicit($json->SRCCTS_tsi);
                    $sheet->getCell('H38')->setValueExplicit('Rp'.number_format($json->SRCCTS_premi, 0, ",", "."));

                    $sheet->getCell('F39')->setValueExplicit($json->personal_accident_driver_rate);
                    $sheet->getCell('G39')->setValueExplicit($json->personal_accident_driver_tsi);
                    $sheet->getCell('H39')->setValueExplicit('Rp'.number_format($json->personal_accident_driver_premi, 0, ",", "."));

                    $sheet->getCell('F40')->setValueExplicit($json->personal_accident_penumpang_4_orang_rate);
                    $sheet->getCell('G40')->setValueExplicit($json->personal_accident_penumpang_4_orang_tsi);
                    $sheet->getCell('H40')->setValueExplicit('Rp'.number_format($json->personal_accident_penumpang_4_orang_premi, 0, ",", "."));

                    $sheet->getCell('F41')->setValueExplicit($json->third_party_rate);
                    $sheet->getCell('G41')->setValueExplicit($json->third_party_tsi);
                    $sheet->getCell('H41')->setValueExplicit('Rp'.number_format($json->third_party_premi, 0, ",", "."));

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