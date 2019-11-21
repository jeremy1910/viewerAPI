<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 19/10/19
 * Time: 15:02
 */

namespace App\Services;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FirstMaxResult
{
        public function __construct(ParameterBagInterface $parameterBag)
        {
            $this->parameterBag = $parameterBag;
        }

        private $parameterBag;

        public function setFirstMaxresult(& $start, & $limit){

            if($start === null OR $limit === null){
                $start = $this->parameterBag->get('DefaultStartResult');
                $limit = $this->parameterBag->get('DefaultMaxResult');;
            }

        }
}