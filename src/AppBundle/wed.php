<?php

namespace DemoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DemoBundle\Entity\Articl;
use DemoBundle\Form\ArticlType;

/**
 * Articl controller.
 *
 * @Route("/articl")
 */
class klk extends Controller
{

    /**
     * Lists all Articl entities.
     *
     * @Route("/", name="articl")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DemoBundle:Articl')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Articl entity.
     *
     * @Route("/", name="articl_create")
     * @Method("POST")
     * @Template("DemoBundle:Articl:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Articl();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('articl_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Articl entity.
     *
     * @param Articl $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Articl $entity)
    {
        $form = $this->createForm(new ArticlType(), $entity, array(
            'action' => $this->generateUrl('articl_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Articl entity.
     *
     * @Route("/new", name="articl_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Articl();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Articl entity.
     *
     * @Route("/{id}", name="articl_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DemoBundle:Articl')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Articl entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Articl entity.
     *
     * @Route("/{id}/edit", name="articl_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DemoBundle:Articl')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Articl entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Articl entity.
    *
    * @param Articl $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Articl $entity)
    {
        $form = $this->createForm(new ArticlType(), $entity, array(
            'action' => $this->generateUrl('articl_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Articl entity.
     *
     * @Route("/{id}", name="articl_update")
     * @Method("PUT")
     * @Template("DemoBundle:Articl:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DemoBundle:Articl')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Articl entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('articl_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Articl entity.
     *
     * @Route("/{id}", name="articl_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DemoBundle:Articl')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Articl entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('articl'));
    }

    /**
     * Creates a form to delete a Articl entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articl_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
