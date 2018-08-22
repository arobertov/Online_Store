<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\Promotion;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Promotion controller.
 *
 * @Route("promotion")
 */
class PromotionController extends Controller
{
    /**
     * Lists all promotion entities.
     *
     * @Route("/", name="promotion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $promotions = $em->getRepository('ShopBundle:Promotion')->findAll();

        return $this->render('@Shop/promotion/index.html.twig', array(
            'promotions' => $promotions,
        ));
    }

	/**
	 * Creates a new promotion entity.
	 *
	 * @Route("/new", name="promotion_new")
	 * @Method({"GET", "POST"})
	 * @param Request $request
	 *
	 * @return RedirectResponse|Response
	 */
    public function newAction(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm('ShopBundle\Form\PromotionType', $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            return $this->redirectToRoute('promotion_show', array('id' => $promotion->getId()));
        }

        return $this->render('@Shop/promotion/new.html.twig', array(
            'promotion' => $promotion,
            'form' => $form->createView(),
        ));
    }

	/**
	 * Finds and displays a promotion entity.
	 *
	 * @Route("/{id}", name="promotion_show")
	 * @Method("GET")
	 * @param Promotion $promotion
	 *
	 * @return Response
	 */
    public function showAction(Promotion $promotion)
    {
        $deleteForm = $this->createDeleteForm($promotion);

        return $this->render('@Shop/promotion/show.html.twig', array(
            'promotion' => $promotion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

	/**
	 * Displays a form to edit an existing promotion entity.
	 *
	 * @Route("/{id}/edit", name="promotion_edit")
	 * @Method({"GET", "POST"})
	 * @param Request $request
	 * @param Promotion $promotion
	 *
	 * @return RedirectResponse|Response
	 */
    public function editAction(Request $request, Promotion $promotion)
    {
        $deleteForm = $this->createDeleteForm($promotion);
        $editForm = $this->createForm('ShopBundle\Form\PromotionType', $promotion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('promotion_edit', array('id' => $promotion->getId()));
        }

        return $this->render('@Shop/promotion/edit.html.twig', array(
            'promotion' => $promotion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

	/**
	 * Deletes a promotion entity.
	 *
	 * @Route("/{id}", name="promotion_delete")
	 * @Method("DELETE")
	 * @param Request $request
	 * @param Promotion $promotion
	 *
	 * @return RedirectResponse
	 */
    public function deleteAction(Request $request, Promotion $promotion)
    {
        $form = $this->createDeleteForm($promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($promotion);
            $em->flush();
        }

        return $this->redirectToRoute('promotion_index');
    }

    /**
     * Creates a form to delete a promotion entity.
     *
     * @param Promotion $promotion The promotion entity
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Promotion $promotion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('promotion_delete', array('id' => $promotion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
