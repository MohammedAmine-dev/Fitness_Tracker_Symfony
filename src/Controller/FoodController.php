<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class FoodController extends AbstractController
{
    #[Route('/food', name: 'app_food', methods: ['GET'])]
    public function index(Request $request, FoodRepository $repository): Response
    {
        $result = $repository->search($request->query->all());

        return $this->render('food/index.html.twig', [
            'foods' => $result['foods'],
            'meta' => $result['meta'],
            'categories' => array_merge(['All'], Food::CATEGORIES),
            'query' => $request->query->all(),
        ]);
    }
}
