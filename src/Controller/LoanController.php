<?php

namespace App\Controller;

use App\Entity\Loan;
use App\Entity\Passenger;
use App\Form\Type\LoanType;
use App\Repository\LoanRepository;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/loan', name: 'loan_')]
class LoanController extends AbstractController
{

    #[Route('/list', name: 'list')]
    public function index(LoanRepository $loanRepository): Response
    {      
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $listLoans = $loanRepository->listerTrajets();
        return $this->render('loan/list.html.twig', [
            "loans" =>$listLoans,
            "title" => "Liste des trajets"
        ]);
    }

    #[Route('/maListe', name: 'maListe')]
    public function maListe(LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $listLoans = $loanRepository->findBy(['driver' => $user->getId()],array('departDate' => 'DESC'));
        
        return $this->render('loan/myList.html.twig', [
            "loans" =>$listLoans,
            "title" => "Mes emprunts"
        ]);
    }

    #[Route('/gestion', name: 'gestion')]
    public function loansValidation(LoanRepository $loanRepository): Response
    {      
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');

        $listLoans = $loanRepository->findBy(array(),array('departDate' => 'DESC'));
        return $this->render('loan/gestion.html.twig', [
            "loans" =>$listLoans,
            "title" => "Validations des emprunts"
        ]);
    }
    
    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager, PersonRepository $personRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $loan = new Loan();
        $loanForm = $this->createForm(LoanType::class, $loan);
        $loanForm->handleRequest($request);

        if($loanForm->isSubmitted() && $loanForm->isValid()) {

            $loan->setStatut('En validation');
            $loan->setDriver($personRepository->find($this->getUser()->getId()));
            $loan->setReturnVehicle(0);
            $loan->setReturnKey(0);

            //sauvegarder la donnée
            $entityManager->persist($loan);
            $entityManager->flush();

            $this->addFlash('success','Demande d\'emprunt créée');
            return $this->redirectToRoute('loan_maListe');
        }

        return $this->render('loan/add.html.twig', [
            'loanForm' => $loanForm->createView(),
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function details(int $id, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $loan = $loanRepository->find($id);
        return $this->render('loan/details.html.twig', [
            "loan"=>$loan,
        ]);
    }

    #[Route('/valider/{id}', name: 'valider')]
    public function valider(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');
        $loan = $loanRepository->find($id);

        if($loan->getStatut() != "En validation" ){
            $this->addFlash(
                'danger',
                'L\'emprunt doit être en validation !'
            );
            return $this->redirectToRoute('loan_gestion');
        }
        $loan->setStatut("Validé");
        $entityManager->persist($loan);
        $entityManager->flush();
        
        return $this->redirectToRoute('loan_gestion');
    }

    #[Route('/refuser/{id}', name: 'refuser')]
    public function refuser(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');
        $loan = $loanRepository->find($id);
        $now = new \DateTime("now");

        if($loan->getStatut() != "En validation" && $loan->getStatut() != "Validé" && $loan->getDepartDate() < $now ){
            $this->addFlash(
                'danger',
                'L\'emprunt doit être en validation ou validé et ne doit pas être en cours !'
            );
            return $this->redirectToRoute('loan_gestion');
        }

        $loan->setReturnVehicle(true);
        $loan->setReturnKey(true);
        $loan->setReturnDate(new \DateTime());
        $loan->setStatut("Refusé");

        $entityManager->persist($loan);
        $entityManager->flush();
        
        return $this->redirectToRoute('loan_gestion');
    }

    #[Route('/annuler/{id}', name: 'annuler')]
    public function annuler(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $loan = $loanRepository->find($id);
        $now = new \DateTime("now");

        if($loan->getStatut() != "Validé" && $loan->getStatut() != "En validation" ){
            $this->addFlash(
                'danger',
                'La demande d\'emprunt doit être en validation ou validé !'
            );
            return $this->redirectToRoute('loan_maListe');
        }

        if($now > $loan->getDepartDate() && $loan->getStatut() != "En validation" ){
            $this->addFlash(
                'danger',
                'Trajet en cours ou passé, Impossible d\'annuler!'
            );
            return $this->redirectToRoute('loan_maListe');
        }   

        if(!$loan->getDriver()->getId() == $user->getId())
        {
            $this->addFlash(
                'danger',
                'Je ne suis pas l\'emprunteur, Impossible d\'annuler!'
            );
            return $this->redirectToRoute('loan_maListe');
        }
        $loan->setStatut("Annulé");
        $loan->setReturnVehicle(true);
        $loan->setReturnKey(true);
        $loan->setReturnDate(new \DateTime());
        $entityManager->persist($loan);
        $entityManager->flush();
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();

        return $this->redirectToRoute('loan_maListe');
    }

    #[Route('/rejoindre/{id}', name: 'rejoindre')]
    public function rejoindre(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $loan = $loanRepository->find($id);
        $now = new \DateTime("now");
        
        if($loan->getStatut() != "Validé" ){
            $this->addFlash(
                'danger',
                'La demande d\'emprunt n\'est pas validé'
            );
            return $this->redirectToRoute('loan_list');
        }
        if($now > $loan->getDepartDate() ){
            $this->addFlash(
                'danger',
                'Trajet en cours ou passé, Impossible de rejoindre !'
            );
            return $this->redirectToRoute('loan_list');
        }
        if( $loan->userInPassengers($user) ){
            $this->addFlash(
                'danger',
                'Je fais déjà partie du trajet, Impossible de rejoindre !'
            );
            return $this->redirectToRoute('loan_list');
        }
        if($loan->getDriver()->getId() == $user->getId())
        {
            $this->addFlash(
                'danger',
                'Je ne suis conducteur, Impossible de rejoindre !'
            );
            return $this->redirectToRoute('loan_list');
        }
        $loan->getPassengers()->add($user);
        $entityManager->persist($loan);
        $entityManager->flush();

        return $this->redirectToRoute('loan_list');
    }
    #[Route('/quitter/{id}', name: 'quitter')]
    public function quitter(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $loan = $loanRepository->find($id);
        $now = new \DateTime("now");

        if( $loan->getStatut() != "Validé" ){
            $this->addFlash(
                'danger',
                'La demande d\'emprunt n\'est pas validé'
            );
            return $this->redirectToRoute('loan_list');
        }
        if( $now > $loan->getDepartDate() ){
            $this->addFlash(
                'danger',
                'Trajet en cours ou passé, Impossible de quitter !'
            );
            return $this->redirectToRoute('loan_list');
        }
        if(!$loan->userInPassengers($user)){
            $this->addFlash(
                'danger',
                'Utilisateur n\'est pas dans le trajet !'
            );
            return $this->redirectToRoute('loan_list');
        }
        $loan->deletePassanger($user);
        $entityManager->persist($loan);
        $entityManager->flush();

        return $this->redirectToRoute('loan_list');
    }
    #[Route('/removePassenger/{idLoan}/{idUser}', name: 'remove_passenger')]
    public function removePassenger(int $idLoan,int $idUser, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository, PersonRepository $personRepository ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $personRepository->find($idUser);
        $loan = $loanRepository->find($idLoan);
        $now = new \DateTime("now");
        
        if($loan->getStatut() != "Validé"  ){
            $this->addFlash(
                'danger',
                'La demande d\'emprunt n\'est pas validé'
            );
            return $this->redirectToRoute('loan_maListe');
        }
        if( $now > $loan->getDepartDate() ){
            $this->addFlash(
                'danger',
                'Trajet en cours ou passé, Impossible de retirer le passager !'
            );
            return $this->redirectToRoute('loan_maListe');
        }
        if(!$loan->userInPassengers($user)){
            $this->addFlash(
                'danger',
                'Utilisateur n\'est pas dans le trajet !'
            );
            return $this->redirectToRoute('loan_maListe');
        }
        $loan->deletePassanger($user);
        $entityManager->persist($loan);
        $entityManager->flush();

        return $this->redirectToRoute('loan_maListe');
    }

    #[Route('/complete/{id}', name: 'complete')]
    public function complete(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $loan = $loanRepository->find($id);

        if( new \DateTime("now") < $loan->getReturnDate() ){
            $this->addFlash(
                'danger',
                'Trajet pas terminer, Impossible de terminer le trajet !'
            );
            return $this->redirectToRoute('loan_maListe');
        }

        if(!$loan->getDriver()->getId() == $user->getId())
        {
            $this->addFlash(
                'danger',
                'Je ne suis pas le demandeur de cet emprunt, Impossible de terminer le trajet !'
            );
            return $this->redirectToRoute('loan_maListe');
        }
        
        $loan->setStatut("Complété");
        $loan->setReturnDate(new \DateTime());
        $entityManager->persist($loan);
        $entityManager->flush();
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();
        return $this->redirectToRoute('loan_maListe');
    }

    #[Route('/rendrev/{id}', name: 'rendreV')]
    public function rendreV(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');

        $loan = $loanRepository->find($id);
        $loan->setReturnVehicle(true);
        $loan->setReturnDate(new \DateTime());

        if($loan->isReturnVehicle() && $loan->isReturnKey()) {
            $loan->setStatut('Soldé');
        }

        $entityManager->persist($loan);
        $entityManager->flush();

        return $this->redirectToRoute('loan_list');
    }
    
    #[Route('/rendrec/{id}', name: 'rendreC')]
    public function rendreC(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');

        $loan = $loanRepository->find($id);
        $loan->setReturnKey(true);

        if($loan->isReturnVehicle() && $loan->isReturnKey()) {
            $loan->setStatut('Soldé');
        }

        $entityManager->persist($loan);
        $entityManager->flush();

        return $this->redirectToRoute('loan_gestion');
    }
}
