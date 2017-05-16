<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\Product;
use GescomBundle\Entity\ProductSupplier;
use GescomBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getRepository('GescomBundle:Product')->findAll();
        return $this->render('@Gescom/Product/product.html.twig', [
            'products'  => $products,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/product/add", name="add_product")
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form  ->isValid()){
            $suppliers = $product->getProductSupplier()["name"];
            $product->resetProductSupplier();
            foreach($suppliers as $supplier){
                $productSupplier = new ProductSupplier();
                $productSupplier->setProduct($product);
                $productSupplier->setSupplier($supplier);
                $em->persist($productSupplier);
                $product->addProductSupplier($productSupplier);
            }
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product');
        }

        return $this->render('@Gescom/Product/addProduct.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @param Request $request
     * @Route("/product/{id}", name="delete_product")
     */

    public function deleteAction(Request $request, $id)
    {

    $em = $this->getDoctrine()->getManager();
    $name = $em->getRepository('GescomBundle:Product')->find($id);
    $em = remove($name);
    $em->flush();

    $url = $this->generateUrl('product');
    return $this->redirect($url);
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @param Request $request
     * @Route("/product/edit/{id}", name="edit_product")
     */
    public function editAction(Request $request, $id)
    {
        $name = $this->getDoctrine()->getRepository('GescomBundle:Product')->find($id);
        $formEdit = $this->CreateForm(ProductType::class,$name);
        $formEdit->handleRequest($request);
        if ($formEdit->isValid() && $formEdit->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($name);
            $em->flush();
            $login->getFlashBag()->add('success', 'Your product is modified');
            $url = $this->generateUrl('product');
            return $this->redirect($url);
        }
        return $this->render('GescomBundle/Product/edit.html.twig', array(
            'name' => $name,
            'description' => $description,
            'category' => $category,
            'productSupplier'  =>$productSupplier,
            'formEdit' => $formEdit->createView(),
        ));
        }
}
