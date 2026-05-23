<?php
namespace App\Controller;

use App\Form\GoalType;
use App\Service\GoalsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GoalsController extends AbstractController
{
    #[Route('/goals', name: 'app_goals')]
    public function index(Request $request, GoalsService $goalsService): Response
    {
        // Mock user for testing since security is not fully set up
        $user = $this->getUser(); 

        $goal = $goalsService->getOrCreateGoal($user);
        $form = $this->createForm(GoalType::class, $goal);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $goalsService->save($goal);
            $this->addFlash('success', 'Goals updated successfully!');
            return $this->redirectToRoute('app_goals');
        }

        return $this->render('goals/index.html.twig', [
            'form' => $form->createView(),
            'goal' => $goal,
        ]);
    }
}
