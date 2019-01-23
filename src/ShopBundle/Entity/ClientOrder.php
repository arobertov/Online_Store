<?php

namespace ShopBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ShopBundle\Entity\PurchaseProduct;

/**
 * ClientOrder
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ClientOrderRepository")
 */
class ClientOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="order_status", type="string", length=64)
	 */
    private $orderStatus = 'pending';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var string
     *
     * @ORM\Column(name="totalPrice", type="decimal",precision=8, scale=2 )
     */
    private $totalPrice;

	/**
	 * @var User $user
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="orders",cascade={"persist","remove"})
	 * @ORM\JoinColumn(name="user_id",referencedColumnName="id", nullable= true)
	 */
    private $user;

	/**
	 *
	 * @ORM\ManyToMany(targetEntity="ShopBundle\Entity\PurchaseProduct",mappedBy="orders",cascade={"persist","remove"})
	 */
    private $purchaseProducts;

	/**
	 * ClientOrder constructor.
	 * @throws \Exception
	 */
	public function __construct( ) {
		$this->purchaseProducts = new ArrayCollection();
		try {
			$this->dateCreated = new \DateTime( 'now' );
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}
	}


	/**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * @return string
	 */
	public function getOrderStatus(): string {
		return $this->orderStatus;
	}

	/**
	 * @param string $orderStatus
	 *
	 * @return ClientOrder
	 */
	public function setOrderStatus( string $orderStatus ): ClientOrder {
		$this->orderStatus = $orderStatus;

		return $this;
	}
	
    /**
     * Set dateCreated.
     *
     * @param \DateTime $dateCreated
     *
     * @return ClientOrder
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated.
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set totalPrice.
     *
     * @param string $totalPrice
     *
     * @return ClientOrder
     */
    public function setTotalPrice($totalPrice):ClientOrder
    {
        $this->totalPrice = sprintf('%.2f',$totalPrice);

        return $this;
    }

    /**
     * Get totalPrice.
     *
     * @return string
     */
    public function getTotalPrice():string
    {
        return sprintf('%.2f',$this->totalPrice);
    }

	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	public function setUser( User $user ): void {
		$this->user = $user;
	}



    /**
     * Add purchaseProduct.
     *
     * @param PurchaseProduct $purchaseProduct
     *
     * @return ClientOrder
     */
    public function addPurchaseProduct( PurchaseProduct $purchaseProduct)
    {
    	if(!$this->purchaseProducts->contains($purchaseProduct)){
		    $this->purchaseProducts[] = $purchaseProduct;
	    }

        $purchaseProduct->addOrder($this);
        return $this;
    }

    /**
     * Remove purchaseProduct.
     *
     * @param PurchaseProduct $purchaseProduct
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePurchaseProduct( PurchaseProduct $purchaseProduct)
    {
        return $this->purchaseProducts->removeElement($purchaseProduct);
    }

    /**
     * Get purchaseProducts.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPurchaseProducts()
    {
        return $this->purchaseProducts;
    }
}
