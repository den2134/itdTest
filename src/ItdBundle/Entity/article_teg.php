<?php

namespace ItdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="article_tag")
 * @ORM\HasLifecycleCallbacks()
 */

class article_teg{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="id")
     * @ORM\JoinColumn(name="aticle_id", referencedColumnName="id", onDelete="CASCADE")
     * @ORM\Column(type="smallint")
     */
    public $article_id;

    /**
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="name")
     * @ORM\JoinColumn(name="name", referencedColumnName="name", onDelete="CASCADE")
     * @ORM\Column(type="string", length=100)
     */

    public $name;


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
     * Set article
     *
     * @param \ItdBundle\Entity\Article $article
     *
     * @return article_teg
     */
    public function setArticle($article)
    {
        $this->article_id = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \ItdBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set tag
     *
     * @param \ItdBundle\Entity\Tag $tag
     *
     * @return article_teg
     */
    public function setName($tag)
    {
        $this->name = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \ItdBundle\Entity\Tag
     */
    public function getName()
    {
        return $this->name;
    }
}
