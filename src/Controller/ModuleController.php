<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/admin/module', name: 'app_module')]
    public function index(ModuleRepository $repo): Response
    {
        $modules = $repo->findAll();
        // dd($modules);
        Return $this->render('module/index.html.twig', ['controller_name' =>
        'ModuleController','modules'=>$modules,]);

    }

    #[Route('/admin/module/create', name: 'module_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $module = new Module();
        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($module);
            $entityManager->flush();

            return $this->redirectToRoute('app_module');
        }

        return $this->render('module/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/module/{id}/edit', name: 'module_edit')]
public function edit(Module $module, Request $request, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ModuleType::class, $module);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_module');
    }

    return $this->render('module/edit.html.twig', [
        'form' => $form->createView(),
        'module' => $module,
    ]);
    
}

    #[Route('/admin/module/{id}/delete', name: 'module_delete')]
    public function delete(Module $module, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($module);
        $entityManager->flush();

        return $this->redirectToRoute('app_module');
    }
}
