<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 07/10/19
 * Time: 22:36
 */

namespace App\Services;


use App\Repository\VariableRepository;

class HandelTranslatePH
{

    public function __construct(VariableRepository $variableRepository)
    {
        $this->variableRepository = $variableRepository;
    }

    private $variableRepository;

    public function parsePH(){

        $PHs = $this->variableRepository->findPH();



        foreach ($PHs as $PH){


            $result = mb_convert_encoding($PH->getExtension(), 'UTF-16BE', 'UTF-8');
            $this->TranslatePH($PH->getExtension());
        }
    }

    private function TranslatePH(string $xml){

        $translateCalebdar = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];

        $translateAllow = [
            0 => 'Refuser',
            1 => 'Autoriser',
        ];

        $programmes = [];
        $timstampOffset = 31708800/60;
        $prevMinute = 0;

        $xml = simplexml_load_string($xml);

        foreach ($xml->Tranches->anyType as $tranche){

            if($tranche->Minute == 0)
            {
                $prevMinute = $tranche->Minute;
                $prevValeur = $tranche->Valeur;

            }
            else{
                $actual = $tranche->Minute;
                $day1 = gmstrftime("%w", ($prevMinute+$timstampOffset)*60);
                $day2 = gmstrftime("%w", ($actual+$timstampOffset)*60);

                $programmes[] = $translateCalebdar[$day1] . ' ' . gmstrftime("%H H %M mn", ($prevMinute+$timstampOffset)*60). ' à ' . $translateCalebdar[$day2] . ' ' . gmstrftime("%H H %M mn", ($actual+$timstampOffset)*60) . ' '. $translateAllow[(int)$prevValeur];
                $prevMinute = $actual;
                $prevValeur = $tranche->Valeur;

            }

        }

        $actual = 10080;
        $prevValeur = 0;
        $day1 = gmstrftime("%w", ($prevMinute+$timstampOffset)*60);
        $day2 = gmstrftime("%w", ($actual+$timstampOffset)*60);

        $programmes[] = $translateCalebdar[$day1] . ' ' . gmstrftime("%H H %M mn", ($prevMinute+$timstampOffset)*60). ' à ' . $translateCalebdar[$day2] . ' ' . gmstrftime("%H H %M mn", ($actual+$timstampOffset)*60) . ' '. $translateAllow[(int)$prevValeur];




        dd($programmes);
    }

}