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
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="id")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     * */

    protected $article;

    /**
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="id")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     * */

    protected $tag;


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
    public function setArticle(\ItdBundle\Entity\Article $article = null)
    {
        $this->article = $article;

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
    public function setTag(\ItdBundle\Entity\Tag $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \ItdBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}
