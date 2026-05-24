<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\ExerciseLog;
use App\Form\ExerciseLogType;
use App\Form\ExerciseType;
use App\Service\ExerciseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ExerciseController extends AbstractController
{
    #[Route('/exercise', name: 'app_exercise')]
    public function index(ExerciseService $service): Response
    {
        $user = $this->getUser();

        return $this->render('exercise/index.html.twig', [
            'categories' => $service->getCategories(),
            'catalog' => $service->getCatalog(),
            'recentLogs' => $service->getRecentLogs($user),
            'logForm' => $this->createForm(ExerciseLogType::class, new ExerciseLog())->createView(),
        ]);
    }

    #[Route('/exercise/{category}', name: 'app_exercise_category', requirements: ['category' => 'cardio|strength|calisthenics|sports'])]
    public function byCategory(string $category, ExerciseService $service): Response
    {
        return $this->render('exercise/by_category.html.twig', [
            'category' => $category,
            'categories' => $service->getCategories(),
            'exercises' => $service->getCatalog($category),
            'logForm' => $this->createForm(ExerciseLogType::class, new ExerciseLog())->createView(),
        ]);
    }

    #[Route('/exercise/log/add', name: 'app_exercise_log_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addLog(Request $request, ExerciseService $service): Response
    {
        $log = new ExerciseLog();
        $form = $this->createForm(ExerciseLogType::class, $log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->addLog($this->getUser(), $log);
            $this->addFlash('success', 'Exercise logged.');
        }

        return $this->redirectToRoute('app_exercise');
    }

    #[Route('/exercise/log/{id}/delete', name: 'app_exercise_log_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function deleteLog(Request $request, ExerciseLog $log, ExerciseService $service): Response
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete_log_' . $log->getId(), $token)) {
            $service->deleteLog($log, $this->getUser());
        }

        return $this->redirectToRoute('app_exercise');
    }

    #[Route('/exercise/new', name: 'app_exercise_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, ExerciseService $service): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveCatalogEntry($exercise);
            $this->addFlash('success', 'Exercise added to catalog.');

            return $this->redirectToRoute('app_exercise');
        }

        return $this->render('exercise/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/exercise/{id}/edit', name: 'app_exercise_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Exercise $exercise, Request $request, ExerciseService $service): Response
    {
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->saveCatalogEntry($exercise);
            $this->addFlash('success', 'Exercise updated.');

            return $this->redirectToRoute('app_exercise');
        }

        return $this->render('exercise/edit.html.twig', [
            'form' => $form->createView(),
            'exercise' => $exercise,
        ]);
    }
}
