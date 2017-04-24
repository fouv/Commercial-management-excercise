<?php

namespace GescomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $totalProducts = $this->getDoctrine()
            ->getRepository('GescomBundle:Product')
            ->createQueryBuilder('p')
            ->select('COUNT(p)');
        $totalSuppliers = $this->getDoctrine()
            ->getRepository('GescomBundle:Supplier')
            ->createQueryBuilder('s')
            ->select('COUNT(s)');
        $totalCategories = $this->getDoctrine()
            ->getRepository('GescomBundle:Category')
            ->createQueryBuilder('c')
            ->select('COUNT(c)');

        return $this->render('GescomBundle:Main:index.html.twig', [
            'totalProducts'     => $totalProducts->getQuery()->getSingleScalarResult(),
            'totalSuppliers'    => $totalSuppliers->getQuery()->getSingleScalarResult(),
            'totalCategories'   => $totalCategories->getQuery()->getSingleScalarResult(),
        ]);
    }
}
