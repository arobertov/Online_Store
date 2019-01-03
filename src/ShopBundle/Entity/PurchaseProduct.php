<?php


namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PurchaseProduct
 * @package ShopBundle\Entity
 *
 * @ORM\Table(name="purchase_products")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\PurchaseProductRepository")
 */
class PurchaseProduct {
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 */
	private $id;

	/**
	 * @var Product
	 */
	private $product;

	/**
	 * @var string
	 *
	 *
	 * @ORM\Column(name="product_title",type="string",length=255)
	 */
	private $productTitle;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	private $productQuantity;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $realPrice;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $productPrice;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $productDiscount;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $imagePath;

	/**
	 * @var string
	 */
	private $subtotal;

	/**
	 * @ORM\ManyToMany(targetEntity="ShopBundle\Entity\ClientOrder",inversedBy="purchaseProducts",cascade={"persist","remove"})
	 * @ORM\JoinTable("orders_products")
	 */
	private $orders;

	/**
	 * ProductCart constructor.
	 *
	 * @param Product $product
	 */
	public function __construct( Product $product ) {
		$this->product = $product;
		$this->id = $product->getId();
		$this->productTitle = $product->getTitle();
		$this->imagePath = $product->getFirstImage()!=null?$product->getFirstImage()->getPath():null;
		$this->productPrice = $product->getPrice();
		$this->productDiscount = $product->getPrice()*$product->getPromotion()->getDiscount();
		$this->realPrice = $product->getPrice();
		$this->orders = new ArrayCollection();
	}


	public function getId(){
		return $this->id;
	}

	/**
	 * @return Product
	 */
	public function getProduct(): Product {
		return $this->product;
	}

	/**
	 * @param Product $product
	 */
	public function setProduct( Product $product ): void {
		$this->product = $product;
	}

	/**
	 * @return string|null
	 */
	public function getProductTitle(): ?string {
		return $this->productTitle;
	}

	/**
	 * @param string $productTitle
	 */
	public function setProductTitle( string $productTitle ): void {
		$this->productTitle = $productTitle;
	}

	/**
	 * @return int|null
	 */
	public function getProductQuantity(): ?int {
		return $this->productQuantity;
	}

	/**
	 * @param int $productQuantity
	 */
	public function setProductQuantity( int $productQuantity ): void {
		$this->productQuantity = $productQuantity;
	}

	/**
	 * @return string
	 */
	public function getRealPrice(): string {
		return sprintf ("%.2f",$this->realPrice*$this->getProductQuantity());
	}

	/**
	 * @param string $realPrice
	 *
	 * @return PurchaseProduct
	 */
	public function setRealPrice( string $realPrice ): PurchaseProduct {

		$this->realPrice = $realPrice;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getProductPrice(): string {
		return sprintf ("%.2f", $this->productPrice);
	}

	/**
	 * @param string $productPrice
	 */
	public function setProductPrice( string $productPrice ): void {
		$this->productPrice = $productPrice;
	}

	/**
	 * @return string
	 */
	public function getProductDiscount(): ?string {
			return sprintf ("%.2f",$this->productDiscount * $this->getProductQuantity());
	}

	/**
	 * @param string $productDiscount
	 *
	 * @return PurchaseProduct
	 */
	public function setProductDiscount( string $productDiscount ): PurchaseProduct {
		$this->productDiscount = $productDiscount;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getImagePath(): ?string {
		return $this->imagePath;
	}

	/**
	 * @param string $imagePath
	 */
	public function setImagePath( string $imagePath ): void {
		$this->imagePath = $imagePath;
	}

	/**
	 * @return string
	 */
	public function getSubtotal(): string {
		return sprintf ("%.2f",($this->getRealPrice() - $this->getProductDiscount()));
	}

	private function setSubtotal( ): void {
		$this->subtotal = ($this->getRealPrice() - $this->getProductDiscount());
	}

	/**
	 * @return ArrayCollection
	 */
	public function getOrders(){
		return $this->orders;
	}

	/**
	 * @param ClientOrder $order
	 *
	 * @return $this
	 */
	public function addOrder(ClientOrder $order){
		$this->orders[]= $order;
		//$order->addPurchaseProduct($this);
		return $this;
	}

	public function removeOrder(ClientOrder $order){
		if(!$this->orders->contains($order)){
			return;
		}
		$this->orders->removeElement($order);
		//$order->removePurchaseProduct($this);
	}
}