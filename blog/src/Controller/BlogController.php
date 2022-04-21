<?php

namespace App\Controller;

use Exception;
use App\Entity\Article;
use App\Form\FilterType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/liste-des-articles", name="blog_list")
     */
    public function list(Request $request, ArticleRepository $repo): Response
    {

        $filter = $this->createForm(FilterType::class);
        $filter->handleRequest($request);
        $articles = $repo->findAll();

        if($filter->isSubmitted() && $filter->isValid()){
            $category = $filter['category']->getData();
            $order = ($filter['dateOrder']->getData()? 'ASC' : 'DESC');
            $tag = $filter['tag']->getData();
            $articles = $repo->filterArticle($category, $order, $tag);
        }

        return $this->render('blog/list.html.twig', [
            'articles' => $articles,
            'filter' => $filter->createView()
        ]);
    }

        /**
     * @Route("/nouvelle-article", name="blog_new")
     */
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);//Opérateur de résolution de portée
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugger->slug($article->getTitle().'-'.rand(100,500)) ;
            $article->setSlug($slug);

            $em->persist($article);

            try{
                $em->flush();
                $this->addFlash('success', 'Création de l\'article réussie !');
            }catch(Exception $e){
                $this->addFlash('danger', 'Echec lors de la création de l\'article !');
                return $this->redirectToRoute('blog_new');
            }

            return $this->redirectToRoute('blog_list');

        }

        return $this->render('blog/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{slug}", name="blog_show")
     */
    public function show(ArticleRepository $repo, string $slug): Response
    {
        $article = $repo->findOneBy(['slug' => $slug]);

        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/supprimer-article/{slug}", name="blog_delete")
     */
    public function delete(Article $article, EntityManagerInterface $em): Response
    {
     
        $em->remove($article);
       
        try{
            $em->flush();
            $this->addFlash('success', 'Article supprimé !');

        }catch(Exception $e){
            $this->addFlash('danger', 'Echec lors de la suppression de l\'article !');
        }
        

        return $this->redirectToRoute('blog_list');

    }

    /**
     * @Route("/modifier-article/{slug}", name="blog_edit")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em): Response
    {
     
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $article->setUpdatedAt(new \Datetime);
            try{
                $em->flush();
                $this->addFlash('success', 'Article modifié !');

            }catch(Exception $e){
                $this->addFlash('danger', 'Echec lors de la modification de l\'article !');
            }
            
            return $this->redirectToRoute('blog_list');
        }


        return $this->render('blog/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
