<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Compra
 *
 * @ORM\Table(name="compra", indexes={@ORM\Index(name="fk_compra_produto1_idx", columns={"produto_id"}), @ORM\Index(name="fk_compra_fornecedor1_idx", columns={"fornecedor_id"})})
 * @ORM\Entity
 */
class Compra
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantidade", type="integer", nullable=false)
     */
    private $quantidade;

    /**
     * @var float
     *
     * @ORM\Column(name="value_product", type="float", precision=10, scale=2, nullable=false)
     */
    private $valueProduct;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_criacao", type="date", nullable=false)
     */
    private $dataCriacao;

    /**
     * @var \Application\Entity\Fornecedor
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Fornecedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fornecedor_id", referencedColumnName="id")
     * })
     */
    private $fornecedor;

    /**
     * @var \Application\Entity\Produto
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Produto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="produto_id", referencedColumnName="id")
     * })
     */
    private $produto;



    /**
     * Set id
     *
     * @param integer $id
     * @return Compra
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        /*
        $logger = new \Zend\Log\Logger;
        $writer = new \Zend\Log\Writer\Stream(ROOT_PATH.'/log/log.txt');
        $logger->addWriter($writer);

        $logger-> info("\n" . __LINE__ ."vai mandar o id: " . print_r($this->ida, true));
        */
        return $this->id;
    }

    /**
     * Set quantidade
     *
     * @param integer $quantidade
     * @return Compra
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get quantidade
     *
     * @return integer
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set valueProduct
     *
     * @param float $valueProduct
     * @return Compra
     */
    public function setValueProduct($valueProduct)
    {
        $this->valueProduct = $valueProduct;

        return $this;
    }

    /**
     * Get valueProduct
     *
     * @return float
     */
    public function getValueProduct()
    {
        return $this->valueProduct;
    }

    /**
     * Set dataCriacao
     *
     * @param \DateTime $dataCriacao
     * @return Compra
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;

        return $this;
    }

    /**
     * Get dataCriacao
     *
     * @return \DateTime
     */
    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    /**
     * Set fornecedor
     *
     * @param \Application\Entity\Fornecedor $fornecedor
     * @return Compra
     */
    public function setFornecedor(\Application\Entity\Fornecedor $fornecedor)
    {
        $this->fornecedor = $fornecedor;

        return $this;
    }

    /**
     * Get fornecedor
     *
     * @return \Application\Entity\Fornecedor
     */
    public function getFornecedor()
    {
        return $this->fornecedor;
    }

    /**
     * Set produto
     *
     * @param \Application\Entity\Produto $produto
     * @return Compra
     */
    public function setProduto(\Application\Entity\Produto $produto)
    {
        $this->produto = $produto;

        return $this;
    }

    /**
     * Get produto
     *
     * @return \Application\Entity\Produto
     */
    public function getProduto()
    {
        return $this->produto;
    }
}
