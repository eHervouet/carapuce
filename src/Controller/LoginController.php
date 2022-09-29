<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\PersonType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PersonRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/user_list', name: 'user_list')]
    public function list(PersonRepository $personRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $listPerson = $personRepository->findAll();
        
        return $this->render('person/list.html.twig', [
            "persons" =>$listPerson
        ]);
    }

    #[Route('/user/modifier/{id}', name: 'user_modify')]
    public function modifier(int $id, EntityManagerInterface $entityManager, Request $request, PersonRepository $personRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $person = $personRepository->find($id);

        $form = $this->createForm(PersonType::class, $person);

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {

            $person = $form->getData();

            $plaintextPassword = $person->getPassword();

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $person,
                $plaintextPassword
            );
            $person->setPassword($hashedPassword);

            $person->setRoles([$form->get('role')->getData()]);

            $entityManager->persist($person);
            $entityManager->flush();
            $pathInfo = $request->getPathInfo();
            $requestUri = $request->getRequestUri();

            return $this->redirectToRoute('user_list');
        }
        else
        {
            return $this->render('person/modify.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
}
