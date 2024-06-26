<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class IngredientController extends AbstractController
{
    private $repository;
    private $em;
    private $errors;
    public function __construct(IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager)
    {
        $this->repository = $ingredientRepository;
        $this->em = $entityManager;
    }

    /**
     * This function display all ingredients
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $this->repository->findBy([], ['id' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * This function show a form which create an ingredient
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient/nouveau', name: 'app_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $this->em->persist($ingredient);
            $this->em->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient ' . $ingredient->getName() . 'a été modifié avec succès !'
            );
            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * This controller edits the ingredient
     * 
     * @param Request $request
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Route('/ingredient/edition/{id}', name: 'app_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();

            $this->em->persist($ingredient);
            $this->em->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient ' . $ingredient->getName() . 'a été modifié avec succès !'
            );
            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
            'ingredient' => $ingredient
        ]);
    }

    /**
     * This controller delete the ingredient
     * 
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Route('/ingredient/delete/{id}', name: 'app_delete', methods: ['GET'])]
    function delete(Ingredient $ingredient): Response
    {
        $this->em->remove($ingredient);
        $this->em->flush();

        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succès !'
        );
        return $this->redirectToRoute('app_ingredient');
    }
}
