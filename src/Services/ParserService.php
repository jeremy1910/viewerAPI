<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 05/10/19
 * Time: 23:40
 */

namespace App\Services;


use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParserService
{

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;


    }



    protected $parameterBag;
    protected $reader;
    protected $stmt;
    protected $installation;

    static $badges = array();
    static $profils = array();
    static $variables = array();
    static $glecteurs = array();

    public function init(string $toParse, \App\Entity\Installation $installation){

        $this->installation = $installation;
        $this->reader = Reader::createFromPath($this->parameterBag->get('BaseInstallationPath').$this->parameterBag->get($toParse), 'r');
        $this->reader->setDelimiter(';');
        $this->reader->setHeaderOffset($this->parameterBag->get('default_header_offset'));

        $this->stmt = (new Statement())
            ->offset($this->parameterBag->get('default_data_offset_csv'))
        ;
        return $this;
    }


    protected function protectString(string $toProtect) :string
    {
        return str_replace("\\n", '', $toProtect);
    }

}