<?php
/**
 * Created by PhpStorm.
 * User: jeje
 * Date: 19/10/19
 * Time: 19:53
 */

namespace App\Services;



use App\Repository\BadgeRepository;
use App\Repository\GlecteurRepository;
use App\Repository\InstallationRepository;
use App\Repository\ProfilRepository;
use App\Repository\VariableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class Installation
{


    public function __construct(ParameterBagInterface $parameterBag,
                                Filesystem $filesystem,
                                HandelParserService $handelParserService,
                                InstallationRepository $installationRepository,
                                EntityManagerInterface $entityManager,
                                ValidatorInterface $validator,
                                GlecteurRepository $glecteurRepository,
                                VariableRepository $variableRepository,
                                BadgeRepository $badgeRepository,
                                ProfilRepository $profilRepository
    ){
        $this->parameterBag = $parameterBag;
        $this->filesystem = $filesystem;
        $this->handelParserService = $handelParserService;
        $this->installationRepository = $installationRepository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->glecteurRepository = $glecteurRepository;
        $this->variableRepository = $variableRepository;
        $this->profilRepository = $profilRepository;
        $this->badgeRepository = $badgeRepository;
    }


    private $installationPath;
    private $zipedInstallationFiles;
    private $glecteurRepository;
    private $variableRepository;
    private $badgeRepository;
    private $profilRepository;


    private $parameterBag;
    private $filesystem;
    private $handelParserService;
    private $installationRepository;
    private $entityManager;
    private $validator;
    /**
     * @var $installation \App\Entity\Installation
     */
    private $installation;


    public function init(\App\Entity\Installation $installation = null){
        is_null($installation) ? $this->installation = new \App\Entity\Installation() : $this->installation = $installation;
        return $this;
    }

    public function addInstallation(Request $request)
    {
        /**
         * @var $file UploadedFile
         *
         */

        $file = $request->files->get('upload');
        if ($file->getClientMimeType() !== "application/zip") {
            throw new \Exception("Fichier zip accepté uniquement");
        }




        //$installation->setPath($this->parameterBag->get('BaseInstallationPath') . $installation->getName(). '/');


        try {
            $this->installation->setName($request->request->get("installationName"));
            $errors = $this->validator->validate($this->installation);
            if (count($errors) > 0) {

                $errorsString = (string) $errors;

                throw new \Exception($errorsString);
            }
            //On vérifie si l'installation existe déjà
            if($this->isInstallationExist()){
                throw new \Exception("installation deja existante, pour la modifier merci de passer par l'option de mise à jour");
            }

            //On décompresse le ficheir zip et on le supprime
            $this->handelZipFile($file);

            //On vérifier si tous les fichiers requis sont présents
            $this->checkInstallationValidity();

            //On ajoute l'installation à la base de donnés
            $this->addInInstallationListDB();
            $this->loadInstallation();



        } catch (\Exception $e) {

            $this->entityManager->remove($this->installation);
            $this->entityManager->flush();
            throw new \Exception($e->getMessage());
        }finally{
            $this->filesystem->remove($this->parameterBag->get('BaseInstallationPath'));
        }


    }

    public function updateInstallation(Request $request){
        $file = $request->files->get('upload');

        if ($file->getClientMimeType() !== "application/zip") {
            throw new \Exception("Fichier zip accepté uniquement");
        }

        try {
            $this->handelZipFile($file);

            $this->checkInstallationValidity();
            $this->removeInstallation();
            $this->loadInstallation();



        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }finally{
            $this->filesystem->remove($this->parameterBag->get('BaseInstallationPath'));
        }


    }


    public function openInstallation(\App\Entity\Installation $installation)
    {
        try{
            $this->loadInstallation($installation);

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    private function handelZipFile(UploadedFile $file){
        //On déplace le fichier dans le répetoir par defaut + le nom de l'installation et on le décompresse
        try{
            $this->filesystem->remove($this->parameterBag->get('BaseInstallationPath'));
            $file->move($this->parameterBag->get('BaseInstallationPath'), $this->installation->getName().'.zip');
            $zip = new \ZipArchive();
            $zip->open($this->parameterBag->get('BaseInstallationPath').$this->installation->getName().'.zip');
            $zip->extractTo($this->parameterBag->get('BaseInstallationPath'));
            $zip->close();

        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    private function removeInstallation(){

        $glecteurs = $this->glecteurRepository->findBy(['installation' => $this->installation->getId()]);
        $variables = $this->variableRepository->findBy(['installation' => $this->installation->getId()]);
        $profils = $this->profilRepository->findBy(['installation' => $this->installation->getId()]);
        $badges = $this->badgeRepository->findBy(['installation' => $this->installation->getId()]);

        foreach ($glecteurs as $glecteur){
            $this->entityManager->remove($glecteur);
        }
        foreach ($variables as $variable){
            $this->entityManager->remove($variable);
        }
        foreach ($badges as $badge){
            $this->entityManager->remove($badge);
        }
        foreach ($profils as $profil){
            $this->entityManager->remove($profil);
        }

        $this->entityManager->flush();


    }
    private function addInInstallationListDB(){
        $this->entityManager->persist($this->installation);
        $this->entityManager->flush();
    }



    private function isInstallationExist(){

        return $this->installationRepository->findOneBy(['name' => $this->installation->getName()]) ? true : false;

    }

    private function loadInstallation(){

        if($this->installation){

            $this->handelParserService->parseFiles($this->installation);
        }
        else{
            throw new \Exception($this->installation->getName()." n'existe pas");
        }
    }


    private function checkInstallationValidity(){
        $requiredFiles = [
            $this->parameterBag->get('Glecteur'),
            $this->parameterBag->get('Variable'),
            $this->parameterBag->get('GlecteurDef'),
            $this->parameterBag->get('Profil'),
            $this->parameterBag->get('ProfilAcces'),
            $this->parameterBag->get('Badge'),
            $this->parameterBag->get('BadgeProfil'),
        ] ;

        foreach ($requiredFiles as $file){
            if(!$this->filesystem->exists($this->parameterBag->get('BaseInstallationPath').$file))
            {
                throw new  \Exception("Installation not valide file : ".$file." not found in ".$this->parameterBag->get('BaseInstallationPath'));
            }
        }
        return true;
    }
}