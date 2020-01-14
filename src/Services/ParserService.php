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
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParserService
{

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
		$this->logger = $logger;

    }



    protected $parameterBag;
    protected $reader;
    protected $stmt;
	protected $logger;
	/**
	 * @var $installation \App\Entity\Installation
	 */
    protected $installation;

    static $badges = array();
    static $profils = array();
    static $variables = array();
    static $glecteurs = array();

	static $badges2 = array();
	static $profils2 = array();
	static $variables2 = array();
	static $glecteurs2 = array();

    public function init(string $toParse, \App\Entity\Installation $installation){

        $this->installation = $installation;
        $this->reader = Reader::createFromPath($this->parameterBag->get('BaseInstallationPath').$this->parameterBag->get($toParse), 'r');
        $this->reader->setDelimiter(';');
        $this->reader->setOutputBOM(Reader::BOM_UTF8);
        $this->reader->addStreamFilter('convert.iconv.ISO-8859-15/UTF-8');
        $this->reader->setHeaderOffset($this->parameterBag->get('default_header_offset'));

        $this->stmt = (new Statement())
            ->offset($this->parameterBag->get('default_data_offset_csv'))
        ;
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        return $this;
    }


    protected function protectString(string $toProtect) :string
    {
        return str_replace("\\n", '', $toProtect);
    }

}