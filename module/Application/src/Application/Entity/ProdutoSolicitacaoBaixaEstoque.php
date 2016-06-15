<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProdutoSolicitacaoBaixaEstoque
 *
 * @ORM\Table(name="produto_solicitacao_baixa_estoque", indexes={@ORM\Index(name="fk_produto_solicitacao_baixa_produto1_idx", columns={"produto_id"}), @ORM\Index(name="fk_produto_solicitacao_baixa_estoque_usuario1_idx", columns={"usuario_id"})})
 * @ORM\Entity
 */
class ProdutoSolicitacaoBaixaEstoque
{
    /**
     * @var integer
     *
     * @ORM\Column(name="di", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $di;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantidade", type="integer", nullable=false)
     */
    private $quantidade;

    /**
     * @var \Application\Entity\Usuario
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;

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
     * Set di
     *
     * @param integer $di
     * @return ProdutoSolicitacaoBaixaEstoque
     */
    public function setDi($di)
    {
        $this->di = $di;

        return $this;
    }

    /**
     * Get di
     *
     * @return integer 
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * Set quantidade
     *
     * @param integer $quantidade
     * @return ProdutoSolicitacaoBaixaEstoque
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
     * Set usuario
     *
     * @param \Application\Entity\Usuario $usuario
     * @return ProdutoSolicitacaoBaixaEstoque
     */
    public function setUsuario(\Application\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Application\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set produto
     *
     * @param \Application\Entity\Produto $produto
     * @return ProdutoSolicitacaoBaixaEstoque
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
