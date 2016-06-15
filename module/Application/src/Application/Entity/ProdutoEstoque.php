<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProdutoEstoque
 *
 * @ORM\Table(name="produto_estoque", indexes={@ORM\Index(name="fk_produto_estoque_produto1_idx", columns={"produto_id"})})
 * @ORM\Entity
 */
class ProdutoEstoque
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_criacao", type="datetime", nullable=false)
     */
    private $dataCriacao;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantidade", type="integer", nullable=false)
     */
    private $quantidade;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantidade_baixa", type="integer", nullable=true)
     */
    private $quantidadeBaixa;

    /**
     * @var \Application\Entity\Produto
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Produto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="produto_id", referencedColumnName="id")
     * })
     */
    private $produto;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dataCriacao
     *
     * @param \DateTime $dataCriacao
     * @return ProdutoEstoque
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
     * Set quantidade
     *
     * @param integer $quantidade
     * @return ProdutoEstoque
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
     * Set quantidadeBaixa
     *
     * @param integer $quantidadeBaixa
     * @return ProdutoEstoque
     */
    public function setQuantidadeBaixa($quantidadeBaixa)
    {
        $this->quantidadeBaixa = $quantidadeBaixa;

        return $this;
    }

    /**
     * Get quantidadeBaixa
     *
     * @return integer 
     */
    public function getQuantidadeBaixa()
    {
        return $this->quantidadeBaixa;
    }

    /**
     * Set produto
     *
     * @param \Application\Entity\Produto $produto
     * @return ProdutoEstoque
     */
    public function setProduto(\Application\Entity\Produto $produto = null)
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
