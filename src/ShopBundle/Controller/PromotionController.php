<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\Promotion;
use ShopBundle\Services\PromotionServiceInterface;
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
	 * @var PromotionServiceInterface
	 */
	private $promotionService;

	/**
	 * PromotionController constructor.
	 *
	 * @param PromotionServiceInterface $promotionService
	 */
	public function __construct( PromotionServiceInterface $promotionService ) {
		$this->promotionService = $promotionService;
	}


	/**
     * Lists all promotion entities.
     *
     * @Route("/", name="promotion_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        //$em = $this->getDoctrine()->getManager();

        $promotions = $this->promotionService->getAllPromotion();

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
            try{
            	$this->addFlash('success',$this->promotionService->createPromotion($promotion));
            }catch (\Exception $e){
            	$this->addFlash('danger',$e->getMessage());
            }

            return $this->redirectToRoute('promotion_index');
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
        return $this->render('@Shop/promotion/show.html.twig', array(
            'promotion' => $promotion,
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
        $editForm = $this->createForm('ShopBundle\Form\PromotionType', $promotion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            try {
            	$this->addFlash('success',$this->promotionService->editPromotion($promotion));
            } catch (\Exception $e){
            	$this->addFlash('danger',$e->getMessage());
            }

            return $this->redirectToRoute('promotion_index');
        }

        return $this->render('@Shop/promotion/edit.html.twig', array(
            'promotion' => $promotion,
            'edit_form' => $editForm->createView(),
        ));
    }

	/**
	 * Deletes a promotion entity.
	 *
	 * @Route("/{id}/delete", name="promotion_delete")
	 * @param Promotion $promotion
	 *
	 * @return RedirectResponse
	 */
    public function deleteAction(Promotion $promotion)
    {
        try {
        	$this->addFlash('success',$this->promotionService->removePromotion($promotion));
        } catch (\Exception $e){
        	$this->addFlash('danger',$e->getMessage());
        }

        return $this->redirectToRoute('promotion_index');
    }
}
