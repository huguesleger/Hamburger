<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Burger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Burger controller.
 *
 * @Route("burger")
 */
class BurgerController extends Controller
{
    /**
     * Lists all burger entities.
     *
     * @Route("/", name="burger_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $burgers = $em->getRepository('AppBundle:Burger')->findAll();

        return $this->render('burger/index.html.twig', array(
            'burgers' => $burgers,
        ));
    }

    /**
     * Creates a new burger entity.
     *
     * @Route("/new", name="burger_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $burger = new Burger();
        $form = $this->createForm('AppBundle\Form\BurgerType', $burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($burger);
            $em->flush();

            return $this->redirectToRoute('burger_show', array('id' => $burger->getId()));
        }

        return $this->render('burger/new.html.twig', array(
            'burger' => $burger,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a burger entity.
     *
     * @Route("/{id}", name="burger_show")
     * @Method("GET")
     */
    public function showAction(Burger $burger)
    {
        $deleteForm = $this->createDeleteForm($burger);

        return $this->render('burger/show.html.twig', array(
            'burger' => $burger,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing burger entity.
     *
     * @Route("/{id}/edit", name="burger_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Burger $burger)
    {
        $deleteForm = $this->createDeleteForm($burger);
        $editForm = $this->createForm('AppBundle\Form\BurgerType', $burger);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('burger_edit', array('id' => $burger->getId()));
        }

        return $this->render('burger/edit.html.twig', array(
            'burger' => $burger,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a burger entity.
     *
     * @Route("/{id}", name="burger_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Burger $burger)
    {
        $form = $this->createDeleteForm($burger);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($burger);
            $em->flush();
        }

        return $this->redirectToRoute('burger_index');
    }

    /**
     * Creates a form to delete a burger entity.
     *
     * @param Burger $burger The burger entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Burger $burger)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('burger_delete', array('id' => $burger->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
