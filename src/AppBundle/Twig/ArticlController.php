<?php
/**
 * Created by PhpStorm.
 * User: Денис
 * Date: 18.02.2016
 * Time: 17:55
 */

namespace DemoBundle\Controller;


use DemoBundle\Entity\Articl;
use DemoBundle\Form\ArticlType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcherInterface;

class ArticlController extends Controller
{

    public function getArticlManager()
    {
        return $this->get('demo.article_manager');
    }

    /**
     * @return array
     */

    public function getArticlsAction(){
        $articl = $this-$this->getDoctrine()->getRepository('DemoBundle:Articl')->findAll();

        return array('articls' => $articl);
    }

    /**
     * @param Articl $art
     * @return array
     * @View()
     * @ParamConverter("articl", class="DemoBundle:Articl")
     */

    public function getArticlActions(Articl $art){
        return array('articl' => $art);
    }

    /**
     * @return \Symfony\Component\Form\Form
     * @View()
     */

    public function newArticlAction(){
        return $this->createForm(new ArticlType());
    }

    public function postArticlAction(Request $request){
        $art = new Articl();
        $form = $this->createForm(new ArticlType(), $art);

        $form->submit($request);
        if($form->isValid()){
            $this->getArticlManager()->set($art);
            return $this->routeRedirectView('get_articl', array('id' => $art->getId()));
        }

        return array(
            'form' => $form
        );
    }
}