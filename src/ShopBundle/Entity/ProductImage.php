<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductImage
 *
 * @ORM\Table(name="product_image")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ProductImageRepository")
 */
class ProductImage
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

	/**
	 * @ORM\ManyToMany(targetEntity="ShopBundle\Entity\Product",inversedBy="images")
	 * @ORM\JoinTable("images_products")
	 */
    private $products;

	/**
	 * ProductImage constructor.
	 */
	public function __construct() {
		$this->products = new ArrayCollection;
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
     * Set path.
     *
     * @param string $path
     *
     * @return ProductImage
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

	/**
	 * @return mixed
	 */
	public function getProducts() {
		return $this->products;
	}

	/**
	 * @param mixed $products
	 *
	 * @return ProductImage
	 */
	public function setProducts( $products ): ProductImage {
		$this->products = $products;

		return $this;
	}

	/**
	 * @param Product $product
	 */
	public function addProduct(Product $product){
		$this->products[] = $product;
	}
}
