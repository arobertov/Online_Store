<?php

namespace ShopBundle\Controller;

use ShopBundle\Services\ClientOrderServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ClientOrderController
 * @package ShopBundle\Controller
 *
 * @Route("/dasboard/order/")
 */
class ClientOrderController extends Controller {

	/**
	 * @var ClientOrderServiceInterface $orderService
	 */
	private $orderService;

	/**
	 * ClientOrderController constructor.
	 *
	 * @param ClientOrderServiceInterface $orderService
	 */
	public function __construct( ClientOrderServiceInterface $orderService ) {
		$this->orderService = $orderService;
	}

	/**
	 * @Route("create",name="create_order")
	 */
	public function createOrderAction(){
		return $this->render('@Shop/order/create_order.html.twig');
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 *
	 * @Route("all_orders",name="list_all_orders")
	 */
	public function listAllOrdersAction(){
		$orders = $this->orderService->listAllOrders();
		dump($orders);
		return $this->render('@Shop/order/list_all_orders.html.twig',array(
			'orders'=>$orders
		));
	}
}
