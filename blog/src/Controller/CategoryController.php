<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
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
        $category = $repo->findOneBy(['slug' => $slug]);

        return $this->render('category/show.html.twig', [
            'category' => $category
        ]);
    }

    /**
     * @Route("/supp-category/{slug}", name="category_delete")
     */
    public function delete(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        try{
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimé !');

        }catch(Exception $e){
            $this->addFlash('danger', 'Echec lors de la suppression de la catégorie !');
        }
        

        return $this->redirectToRoute('category_list');
    }

        /**
     * @Route("/nouvelle-category", name="category_new")
     */
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugger->slug($category->getName().'-'.rand(100,500)) ;
            $category->setSlug($slug);

            $em->persist($category);

            try{
                $em->flush($category);
                $this->addFlash('success', 'Catégorie crée !');
            }catch(Exception $e){
                $this->addFlash('success', 'Echec lors de la création de la catégorie  !');
                return $this->redirectToRoute('category_new');
            }

            return $this->redirectToRoute('category_show', array('slug' => $slug));

        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

        /**
     * @Route("/modifier-categorie/{slug}", name="category_edit")
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
     
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            try{
                $em->flush();
                $this->addFlash('success', 'Catégorie modifié !');

            }catch(Exception $e){
                $this->addFlash('danger', 'Echec lors de la modification de la catégorie !');
            }
            return $this->redirectToRoute('category_list');
        }


        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

}
