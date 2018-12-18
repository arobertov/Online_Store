<?php

namespace AppBundle\Controller;

use ShopBundle\Entity\ClientOrder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientOrderController extends Controller {
	public function createClientOrder(){
		$order = new ClientOrder();

	}
}
