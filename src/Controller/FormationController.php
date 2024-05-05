<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\UserRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[Route('/profile/formation', name: 'app_formation')]
    public function index(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findAll();
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/admin/formation/create', name: 'formation_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation');
        }

        return $this->render('formation/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/formation/{id}/edit', name: 'formation_edit')]
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formation');
        }

        return $this->render('formation/edit.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }

    #[Route('/admin/formation/{id}/delete', name: 'formation_delete')]
    public function delete(Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('app_formation');
    }
    
    
    #[Route('/profile/formation/{id}/select/{userId}', name: 'formation_select')]
public function selectFormation(Request $request, Formation $formation, int $userId, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
{
    $user = $userRepository->find($userId);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    $formation->addUser($user);
    $entityManager->flush();

    return $this->redirectToRoute('app_formation');
}
    


#[Route('/profile/{userId}/formations', name: 'user_formations')]
public function getUserFormations(int $userId, UserRepository $userRepository): Response
{
    $user = $userRepository->find($userId);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    $formations = $user->getFormations();

    return $this->render('formation/show.html.twig', [
        'user' => $user,
        'formations' => $formations,
    ]);
}

}
