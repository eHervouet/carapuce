<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\LoanRepository;
use App\Repository\PersonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\LoanType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Loan;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/loan', name: 'loan_')]
class LoanController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function index(LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $listLoans = $loanRepository->findAll();
        
        return $this->render('loan/list.html.twig', [
            "loans" =>$listLoans
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

            $this->addFlash('success','Loan added.');
            return $this->redirectToRoute('loan_list');
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

    #[Route('/annuler/{id}', name: 'annuler')]
    public function annuler(int $id, EntityManagerInterface $entityManager, Request $request, LoanRepository $loanRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $loan = $loanRepository->find($id);

        if($loan->getDriver()->getId() == $user->getId())
        {
            $loan->setStatut("Annulé");
            $loan->setReturnVehicle(true);
            $loan->setReturnKey(true);
            $loan->setReturnDate(new \DateTime());
            $entityManager->persist($loan);
            $entityManager->flush();
            $pathInfo = $request->getPathInfo();
            $requestUri = $request->getRequestUri();
        }

        return $this->redirectToRoute('loan_list');
    }
}
