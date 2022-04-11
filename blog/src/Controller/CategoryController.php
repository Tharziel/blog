<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/liste-des-categories", name="category_list")
     */
    public function list(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();
        return $this->render('category/listc.html.twig', [
            'categories' => $categories
        ]);
    }

        /**
     * @Route("/category/{slug}", name="category_show")
     */
    public function show(CategoryRepository $repo, string $slug): Response
    {
        $article = $repo->findOneBy(['slug' => $slug]);

        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }
}
