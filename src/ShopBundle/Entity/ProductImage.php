<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Image(
     *     mimeTypes={"image/jpeg","image/png"},
     *     maxSize="3M"
     * )
     *
     * @ORM\Column(name="path", type="string", length=255,unique=true)
     */
    private $path;


	/**
	 * @var int
	 *
	 * @ORM\Column(name="image_size",type="integer")
	 */
    private $imageSize;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date_upload",type="datetime")
	 */
    private $dateUpload;

	/**
	 * @ORM\ManyToMany(targetEntity="ShopBundle\Entity\Product",inversedBy="images")
	 * @ORM\JoinTable("images_products")
	 */
    private $products;

	/**
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Category",inversedBy="images")
	 * @ORM\JoinColumn(name="category_id",referencedColumnName="id",nullable=true)
	 */
    private $category;

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

	/**
	 * @return \DateTime
	 */
	public function getDateUpload(): \DateTime {
		return $this->dateUpload;
	}

	/**
	 * @param \DateTime $dateUpload
	 *
	 * @return ProductImage
	 */
	public function setDateUpload( \DateTime $dateUpload ): ProductImage {
		$this->dateUpload = $dateUpload;

		return $this;
	}

	/**
	 * @param int $imageSize
	 *
	 * @return ProductImage
	 */
	public function setImageSize( int $imageSize ): ProductImage {
		$this->imageSize = $imageSize;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getImageSize():int {
		return $this->imageSize;
	}

	/**
	 * @param mixed $category
	 *
	 * @return ProductImage
	 */
	public function setCategory( $category ) {
		$this->category = $category;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCategory() {
		return $this->category;
	}
}
