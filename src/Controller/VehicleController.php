<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/vehicle",name="vehicle_")
 */
class VehicleController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function list(VehicleRepository $VehicleRepository): Response
    {
        $listVehicles = $VehicleRepository->findBy([],['brand' =>'ASC']);
        return $this->render('Vehicle/list.html.twig', [
            "vehicles" =>$listVehicles
        ]);
    }
    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request,EntityManagerInterface $entityManager): Response
    {
        $vehicle = new Vehicle();
        $vehicleForm = $this->createForm(VehicleType::class,$vehicle);
        $vehicleForm->handleRequest($request);
        if($vehicleForm->isSubmitted() && $vehicleForm->isValid())
        {
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
    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(int $id, VehicleRepository $vehicleRepository): Response
    {
        $vehicle = $vehicleRepository->find($id);
        return $this->render('vehicle/details.html.twig', [
            "vehicle"=>$vehicle,
        ]);
    }
    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifier(int $id,EntityManagerInterface $entityManager,Request $request, VehicleRepository $vehicleRepository,UrlGeneratorInterface $urlGenerator): Response
    {
        $user = $this->getUser();
        $this->urlGenerator = $urlGenerator;
       // if( $user != null ) {
            $vehicle = $vehicleRepository->find($id);
            $form = $this->createForm(VehicleType::class, $vehicle);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $vehicle = $form->getData();
                $entityManager->persist($vehicle);
                $entityManager->flush();
                $pathInfo = $request->getPathInfo();
                $requestUri = $request->getRequestUri();

                $url = str_replace($pathInfo, rtrim($pathInfo, ' /add'), $requestUri);

                return $this->redirect($url, 301);
            }
            else
            {
                return $this->render('vehicle/modify.html.twig', [
                    'vehicleForm' => $form->createView()
                ]);
            }
        //}
        //else
        //{
        //    return new RedirectResponse($this->urlGenerator->generate('app_login'));
        //}
    }
}
