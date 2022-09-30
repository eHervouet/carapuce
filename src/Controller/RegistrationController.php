<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Person;
use App\Form\Type\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'registration')]
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, PersonRepository $personRepository): Response
    {

        if(($this->getUser() && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) || count($personRepository->findAll()) == 0) {
            $person = new Person();
            $form = $this->createForm(PersonType::class, $person);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

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

                return $this->render('login/index.html.twig', ['error' => null, 'last_username' => $person->getEmail()]);
            }

            return $this->renderForm('registration/index.html.twig', [
                'form' => $form,
            ]);
        }

        return $this->redirectToRoute('index');
    }
}
