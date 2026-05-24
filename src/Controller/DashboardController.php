<?php

namespace App\Controller;

use App\Repository\ExerciseLogRepository;
use App\Repository\MealRepository;
use App\Repository\GoalRepository; // On réutilise le vrai Repository existant
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
<<<<<<< HEAD
class DashboardController extends AbstractController
=======
#[Route('/dashboard', name:'dashboard')]class DashboardController extends AbstractController
>>>>>>> origin/dashboard
{
    public function __construct(
        private readonly MealRepository         $mealRepository,
        private readonly ExerciseLogRepository  $exerciseLogRepository,
        private readonly GoalRepository         $calorieGoalRepository, // Type GoalRepository !
    ) {}

<<<<<<< HEAD
    #[Route('/dashboard', name: 'app_dashboard')]
=======
    #[Route('/', name: 'app_dashboard')]
>>>>>>> origin/dashboard
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $user = $this->mealRepository->getEntityManager()
            ->getRepository(\App\Entity\User::class)
            ->findOneBy([]);

        // Si tu n'as aucun utilisateur en base de données pour le test :
        if (!$user) {
            return new Response("Attention : Tu dois créer au moins un utilisateur dans ta table 'user' pour tester le dashboard !");
        }
        $today     = new \DateTime();
        $yesterday = new \DateTime('-1 day');

        // Stats
        $caloriesConsumed          = $this->mealRepository->totalCaloriesConsumed($user, $today);
        $caloriesConsumedYesterday = $this->mealRepository->totalCaloriesConsumed($user, $yesterday);
        $caloriesBurned            = $this->exerciseLogRepository->totalCaloriesBurned($user, $today);

        // Cet appel fonctionnera dès qu'on aura ajouté la méthode ci-dessous
        $calorieBudget     = $this->calorieGoalRepository->getDailyCalories($user) ?? 2000;
        $remainingCalories = max(0, $calorieBudget - $caloriesConsumed);

        // ── vs-yesterday comparison ─────────────────────────────────────────────
        if ($caloriesConsumedYesterday > 0) {
            $diff                  = $caloriesConsumed - $caloriesConsumedYesterday;
            $vsYesterdayLabelColor = $diff <= 0 ? 'green' : 'red';
            $vsYesterdayPercentage = round(($diff * 100) / $caloriesConsumedYesterday, 1);
        } else {
            $vsYesterdayLabelColor = 'green';
            $vsYesterdayPercentage = 0;
        }

        // Macros
        $protein = $this->mealRepository->totalProtein($user, $today);
        $carbs   = $this->mealRepository->totalCarbs($user, $today);
        $fat     = $this->mealRepository->totalFat($user, $today);

        // Calorie ring
        $ringPercent  = $calorieBudget > 0 ? min(100, round($caloriesConsumed * 100 / $calorieBudget)) : 0;
        $ringOverDone = $caloriesConsumed > $calorieBudget;

        // Meals
        $breakfastMeals = $this->mealRepository->findByType($user, $today, 'breakfast');
        $lunchMeals     = $this->mealRepository->findByType($user, $today, 'lunch');
        $dinnerMeals    = $this->mealRepository->findByType($user, $today, 'dinner');
        $snackMeals     = $this->mealRepository->findByType($user, $today, 'snack');

        $breakfastCalories = array_sum(array_map(fn($m) => $m->getCalories(), $breakfastMeals));
        $lunchCalories     = array_sum(array_map(fn($m) => $m->getCalories(), $lunchMeals));
        $dinnerCalories    = array_sum(array_map(fn($m) => $m->getCalories(), $dinnerMeals));
        $snackCalories     = array_sum(array_map(fn($m) => $m->getCalories(), $snackMeals));

        $exercises = $this->exerciseLogRepository->findByUserAndDate($user, $today);

        return $this->render('dashboard.html.twig', [
            'user'                  => $user,
            'today'                 => $today,
            'caloriesConsumed'      => $caloriesConsumed,
            'caloriesBurned'        => $caloriesBurned,
            'calorieBudget'         => $calorieBudget,
            'remainingCalories'     => $remainingCalories,
            'vsYesterdayPercentage' => $vsYesterdayPercentage,
            'vsYesterdayLabelColor' => $vsYesterdayLabelColor,
            'protein'               => $protein,
            'carbs'                 => $carbs,
            'fat'                   => $fat,
            'ringPercent'           => $ringPercent,
            'ringOverDone'          => $ringOverDone,
            'breakfastMeals'        => $breakfastMeals,
            'lunchMeals'            => $lunchMeals,
            'dinnerMeals'           => $dinnerMeals,
            'snackMeals'            => $snackMeals,
            'breakfastCalories'     => $breakfastCalories,
            'lunchCalories'         => $lunchCalories,
            'dinnerCalories'        => $dinnerCalories,
            'snackCalories'         => $snackCalories,
            'exercises'             => $exercises,
            'steps'                 => 6420,  // Un faux nombre de pas pour faire joli
            'stepsPercentage'       => 64,
        ]);
    }
}
