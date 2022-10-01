<?php

namespace App\Controller;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Type\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/site', name: 'site_')]
class SiteController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(SiteRepository $siteRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $listSites = $siteRepository->findAll();
        
        return $this->render('site/list.html.twig', [
            "sites" =>$listSites
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $site = new Site();
        $siteForm = $this->createForm(SiteType::class,$site);
        $siteForm->handleRequest($request);

        if($siteForm->isSubmitted() && $siteForm->isValid()) {

            //sauvegarder la donnée
            $entityManager->persist($site);
            $entityManager->flush();

            $this->addFlash('success','Site added.');
            return $this->redirectToRoute('site_details',['id'=>$site->getId()]);
        }

        return $this->render('site/add.html.twig', [
            'siteForm' => $siteForm->createView(),
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function details(int $id, SiteRepository $siteRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $site = $siteRepository->find($id);
        return $this->render('site/details.html.twig', [
            "site"=>$site,
        ]);
    }

    #[Route('/modifier/{id}', name: 'modify')]
    public function modifier(int $id, EntityManagerInterface $entityManager, Request $request, SiteRepository $siteRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();
        $site = $siteRepository->find($id);

        $form = $this->createForm(SiteType::class, $site);

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $site = $form->getData();
            $entityManager->persist($site);
            $entityManager->flush();
            $pathInfo = $request->getPathInfo();
            $requestUri = $request->getRequestUri();

            return $this->redirectToRoute('site_details',['id'=>$site->getId()]);
        }
        else
        {
            return $this->render('site/modify.html.twig', [
                'siteForm' => $form->createView()
            ]);
        }
    }
}
