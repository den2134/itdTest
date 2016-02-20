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
     * @param $id
     * @return array
     */
    public function getAction($id)
    {
        $article = $this->getDoctrine()->getRepository('ItdBundle:Article')->find($id);
        $query = $this->getDoctrine()->getManager()->createQuery(
            'SELECT t.name FROM ItdBundle:article_teg as at JOIN ItdBundle:Tag as t WITH t.id = at.tag_id WHERE at.article_id = :id'
        )->setParameter('id', $id);

        $name = $query->getResult();

        // Добавляем имена, которые связаны с таблицей Article

        foreach($name as $val) {
            $article->setNameTag($article->getNameTag().$val['name'].', ');
        }

        return array('ent' => $article);
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
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $articles = $em->getRepository('ItdBundle:Article')->findAll();

            // Добавляем в input имена, которые связаны с таблицей Article
            /*
            foreach($name as $val) {
                $article->setNameTag($article->getNameTag().$val['name']);
            }*/

            return array('ent' => $articles);

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

        $form = $this->createForm(new ArticleType(), $entity, array("method" => $request->getMethod()));
        $form->handleRequest($request);

        if ($form->isValid()) {
            // Превращаем строку с именами на массив, а также удаляем пробелы
            $names = $entity->getNameTag();
            $names = preg_replace('/\s/','',$names);
            $arr[] = explode(',',$names);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Добавляем связь для нашего набора имен с ід статьи

            $articleID = $entity->getId();
            $this->addNamesToArtticle($articleID, $arr);

            return $this->routeRedirectView('get_articles');
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param $articleID
     * @param $arr
     * Создает связи в таблице для каждого имени(Tag.id) к Article.id
     */

    public function addNamesToArtticle($articleID, $arr){
        foreach($arr as $val) {
            foreach($val as $name) {
                $em = $this->getDoctrine()->getManager();
                $tags = new Tag();
                $tags->setName($name);
                $em->persist($tags);
                $em->flush();

                $a_t = new article_teg();
                $a_t->setTag($tags->getId());
                $a_t->setArticle($articleID);
                $em->persist($a_t);
                $em->flush();
            }
        }
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

        $query = $this->getDoctrine()->getManager()->createQuery(
            'SELECT t.name FROM ItdBundle:article_teg as at JOIN ItdBundle:Tag as t WITH t.id = at.tag_id WHERE at.article_id = :id'
        )->setParameter('id', $id);

        $name = $query->getResult();

        // Добавляем в input имена, которые связаны с таблицей Article
        /*
        foreach($name as $val) {
            $article->setNameTag($article->getNameTag().$val['name']);
        }*/
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

    /**
     * @param Request $request
     * @param $id
     * @return FOSView
     */

    public function putAction(Request $request, $id)
    {
        $entity = $this->getDoctrine()->getRepository('ItdBundle:Article')->find($id);

      /*  $query = $this->getDoctrine()->getManager()->createQuery(
            'SELECT t.id FROM ItdBundle:article_teg as at JOIN ItdBundle:Tag as t WITH t.id = at.tag_id WHERE at.article_id = :id'
        )->setParameter('id', $id);

        $tagID = $query->getResult();*/

        $tag = new Tag();//$this->getDoctrine()->getRepository('ItdBundle:Tag')->find($tagID[0]['id']);

        try {
            $request->setMethod('PATCH');
            $form = $this->createForm(new ArticleType(), $entity, array("method" => $request->getMethod()));
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();

            if ($form->isValid()) {
                $tag->setName($entity->getNameTag());
                $em->persist($tag);
                $em->flush();

                $a_t = new article_teg();
                $a_t->setTag($tag->getId());
                $a_t->setArticle($id);
                $em->persist($a_t);
                $em->flush();

                return $this->routeRedirectView('get_articles');
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
