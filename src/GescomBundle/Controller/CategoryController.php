<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\Category;
use GescomBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="category")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository('GescomBundle:Category')->findAll();
        return $this->render('@Gescom/Category/category.html.twig', [
            'categories'  => $categories,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/category/add", name="add_category")
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form  ->isValid()){
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('@Gescom/Category/addCategory.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }
}
