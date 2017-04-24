<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\Supplier;
use GescomBundle\Form\SupplierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SupplierController extends Controller
{

    /**
     * @param string $name
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/supplier", name="supplier")
     *
     */
    public function indexAction()
    {
        $suppliers = $this->getDoctrine()->getRepository('GescomBundle:Supplier')->findAll();
        return $this->render('@Gescom/Supplier/supplier.html.twig', [
            'suppliers'  => $suppliers,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/supplier/add", name="add_supplier")
     */
    public function addAction(Request $request)
    {
        $supplier = new Supplier();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form  ->isValid()){
            $em->persist($supplier);
            $em->flush();
            return $this->redirectToRoute('supplier');
        }

        return $this->render('@Gescom/Supplier/addSupplier.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }    
}
