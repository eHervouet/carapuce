<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LoanRepository;
use App\Repository\VehicleKeyRepository;
use App\Form\Type\VehicleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vehicle', name: 'vehicle_')]
class VehicleController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(VehicleRepository $vehicleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');

        $listVehicles = $vehicleRepository->findBy([],['brand' =>'ASC']);
        
        return $this->render('Vehicle/list.html.twig', [
            "vehicles" =>$listVehicles
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');

        $vehicle = new Vehicle();
        $vehicleForm = $this->createForm(VehicleType::class,$vehicle);
        $vehicleForm->handleRequest($request);

        if($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {

            //sauvegarder la donnée
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success','Vehicle added.');
            return $this->redirectToRoute('vehicle_details',['id'=>$vehicle->getId()]);
        }

        return $this->render('vehicle/add.html.twig', [
            'vehicleForm' => $vehicleForm->createView(),
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function details(int $id, VehicleRepository $vehicleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $vehicle = $vehicleRepository->find($id);
        return $this->render('vehicle/details.html.twig', [
            "vehicle"=>$vehicle,
        ]);
    }
    #[Route('/supprimer/{id}', name: 'supprimer')]
    public function supprimer(int $id, EntityManagerInterface $entityManager, Request $request, VehicleRepository $vehicleRepository, LoanRepository $loanRepository,VehicleKeyRepository $vehicleKeyRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');
        $vehicle = $vehicleRepository->find($id);
        $listloan = $loanRepository->findBy(['affectedVehicle' => $vehicle->getId()]);
        foreach($listloan as $loan)
        {
            $entityManager->remove($loan);
            $entityManager->flush();
        }
        $listkeys = $vehicleKeyRepository->findBy(['idVehicle'=> $vehicle->getId()]);
        foreach($listkeys as $keys)
        {
            $entityManager->remove($keys);
            $entityManager->flush();
        }
        $entityManager->remove($vehicle);
        $entityManager->flush();
        $listVehicles = $vehicleRepository->findBy([],['brand' =>'ASC']);
        
        return $this->render('Vehicle/list.html.twig', [
            "vehicles" =>$listVehicles
        ]);
    }
    #[Route('/modifier/{id}', name: 'modifier')]
    public function modifier(int $id, EntityManagerInterface $entityManager, Request $request, VehicleRepository $vehicleRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_GESTIONNAIRE');

        $user = $this->getUser();
        $vehicle = $vehicleRepository->find($id);

        $form = $this->createForm(VehicleType::class, $vehicle);

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $vehicle = $form->getData();
            $entityManager->persist($vehicle);
            $entityManager->flush();
            $pathInfo = $request->getPathInfo();
            $requestUri = $request->getRequestUri();

            return $this->redirectToRoute('vehicle_details',['id'=>$vehicle->getId()]);
        }
        else
        {
            return $this->render('vehicle/modify.html.twig', [
                'vehicleForm' => $form->createView()
            ]);
        }
    }
}
