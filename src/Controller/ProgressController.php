<?php
namespace App\Controller;

use App\Entity\WeightLog;
use App\Form\WeightLogType;
use App\Service\ProgressService;
use App\Service\GoalsService;
use App\Repository\MealRepository;
use App\Repository\ExerciseLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProgressController extends AbstractController
{
    #[Route('/progress', name: 'app_progress')]
    public function index(
        Request $request, 
        ProgressService $progressService,
        GoalsService $goalsService,
        MealRepository $mealRepo,
        ExerciseLogRepository $exRepo
    ): Response {
        $user = $this->getUser();

        $weightLog = new WeightLog();
        $form = $this->createForm(WeightLogType::class, $weightLog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $progressService->logWeight($user, $weightLog);
            $this->addFlash('success', 'Weight logged successfully!');
            return $this->redirectToRoute('app_progress');
        }

        $goal = $goalsService->getOrCreateGoal($user);
        $caloriesConsumed = $mealRepo->getCaloriesConsumedToday($user);
        $workoutsThisWeek = $exRepo->countWorkoutsThisWeek($user);

        return $this->render('progress/index.html.twig', [
            'form' => $form->createView(),
            'goal' => $goal,
            'caloriesConsumed' => $caloriesConsumed,
            'workoutsThisWeek' => $workoutsThisWeek,
        ]);
    }

    #[Route('/progress/data', name: 'app_progress_data', methods: ['GET'])]
    public function data(ProgressService $progressService): JsonResponse
    {
        $user = $this->getUser();
        $logs = $progressService->getWeightHistory($user);

        $labels = [];
        $data = [];
        foreach ($logs as $log) {
            $labels[] = $log->getDate()->format('M d');
            $data[] = $log->getWeight();
        }

        return new JsonResponse([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
