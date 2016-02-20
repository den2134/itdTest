<?php

namespace ItdBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use ItdBundle\Entity\Article;
use ItdBundle\Entity\article_teg;
use ItdBundle\Entity\Tag;
use ItdBundle\Form\ArticleType;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Article controller.
 * @RouteResource("Article")
 */
class ArticleRESTController extends FOSRestController
{
    /**
     * Get a Article entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @return Response
     *
     */
    public function getAction(Article $entity)
    {
        return array('ent' => $entity);
    }
    /**
     * Get all Article entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     *
     * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="order_by", nullable=true, array=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
     * @QueryParam(name="filters", nullable=true, array=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            $order_by = $paramFetcher->get('order_by');
            $filters = !is_null($paramFetcher->get('filters')) ? $paramFetcher->get('filters') : array();

            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('ItdBundle:Article')->findAll();
            return array('ent' => $entities);

        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Partial Update to a Article entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */

    public function patchAction(Request $request, Article $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a Article entity.
     *
     * @View(statusCode=204)
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        $deleted = $this->getDoctrine()->getRepository('ItdBundle:Article')->find($id);
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($deleted);
            $em->flush();

            return $this->routeRedirectView('get_articles');

        } catch (Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return Form|\Symfony\Component\Form\FormInterface
     * @View()
     */

    public function newAction(Request $request)
    {
        $entity = new Article();
        $form = $this->createForm(new ArticleType(), $entity, array("method" => $request->getMethod()));
        $form->handleRequest($request);
        return $form;
    }

    /**
     * Create a Article entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     *
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new Article();
        $tags = new Tag();
        $a_t = new article_teg();

        $form = $this->createForm(new ArticleType(), $entity, array("method" => $request->getMethod()));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tags->name = $entity->getNameTag();
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->persist($tags);
            $em->flush();

            // Add to article_tag db

            $a_t->setArticle($entity->getId());
            $a_t->setTag($tags->getId());
            $em->persist($a_t);
            $em->flush();

            return $this->routeRedirectView('get_articles');
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Form|\Symfony\Component\Form\FormInterface
     * @View()
     */

    public function editAction(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository('ItdBundle:Article')->find($id);
        if (false === $article) {
            throw $this->createNotFoundException("Articles does not exist.");
        }

        //$names = $this->getDoctrine()->getRepository('ItdBundle:article_teg')->findBy(array('article_id' => $id));

        $query = $this->getDoctrine()->getManager()->createQuery(
            'SELECT t.name FROM ItdBundle:article_teg as at JOIN ItdBundle:Tag as t WITH t.id = at.tag_id WHERE at.article_id = :id'
        )->setParameter('id', $id);

        $name = $query->getResult();

        foreach($name as $val) {
            $article->setNameTag($val['name']);
        }
        $form = $this->createForm(new ArticleType(), $article);

        return array('form' => $form, 'id' => $id);
    }

    /**
     * Update a Article entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     */

    public function putAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()->getRepository('ItdBundle:Article')->find($id);

        $query = $this->getDoctrine()->getManager()->createQuery(
            'SELECT t.id FROM ItdBundle:article_teg as at JOIN ItdBundle:Tag as t WITH t.id = at.tag_id WHERE at.article_id = :id'
        )->setParameter('id', $id);

        $tagID = $query->getResult();

        $tag = $this->getDoctrine()->getRepository('ItdBundle:Tag')->find($tagID[0]['id']);

        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH');
            $form = $this->createForm(new ArticleType(), $entity, array("method" => $request->getMethod()));
            //$this->removeExtraFields($request, $form);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $tag->setName($entity->getNameTag());
                $em->flush();

                return $entity;
            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */

    public function removeAction(Request $request, $id)
    {
        return $this->deleteAction($request, $id);
    }
}
