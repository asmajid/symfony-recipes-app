<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    private $repository;
    private $em;
    /**
     * Construct
     *
     * @param RecipeRepository $recipeRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(RecipeRepository $recipeRepository, EntityManagerInterface $entityManager)
    {
        $this->repository = $recipeRepository;
        $this->em = $entityManager;
    }

    /**
     * This controller display all recipes 
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recipe', name: 'app_recipe', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $recipes = $paginator->paginate(
            $this->repository->findBy([], ['id' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * This controller delete the ingredient
     * 
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/recipe/delete/{id}', name: 'recipe_delete', methods: ['GET'])]
    function delete(Recipe $recipe): Response
    {
        $this->em->remove($recipe);
        $this->em->flush();

        $this->addFlash(
            'success',
            'Votre recette ' . $recipe->getName() . ' a été supprimé avec succès !'
        );
        return $this->redirectToRoute('app_recipe');
    }
}
