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
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @param Request $request
     * @Route("/category/edit/{id}", name="edit_category")
     */
    public function editAction(Request $request, $id)
    {
        $name = $this->getDoctrine()->getRepository('GescomBundle:Category')->find($id);
        $formEdit = $this->CreateForm(CategoryType::class,$name);
        $formEdit->handleRequest($request);
        if ($formEdit->isValid() && $formEdit->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($name);
            $em->flush();
            $this->addFlash('success', 'Your category has been modified');
            $url = $this->generateUrl('category');
            return $this->redirect($url);
        }
        return $this->render('@Gescom/Category/editCategory.html.twig', array(
            'name' => $name,

            'formEdit' => $formEdit->createView(),
        ));
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @param Request $request
     * @Route("/category/{id}", name="delete_category")
     */

    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $name = $em->getRepository('GescomBundle:Category')->find($id);
        $em = remove($name);
        $em->flush();

        $url = $this->generateUrl('category');
        return $this->redirect($url);
    }
}
