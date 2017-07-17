<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Burger;
use AppBundle\Form\BurgerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Burger controller.
 *
 * @Route("admin/burger")
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

        return $this->render('admin/burger/index.html.twig', array(
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
            $nomDuFichier = md5(uniqid()).".".$burger->getThumbnail()->getClientOriginalExtension();
            $burger->getThumbnail()->move('uploads/img', $nomDuFichier);
            $burger->setThumbnail($nomDuFichier);
            $em = $this->getDoctrine()->getManager();
            $em->persist($burger);
            $em->flush();

            return $this->redirectToRoute('burger_show', array('id' => $burger->getId()));
        }

        return $this->render('admin/burger/new.html.twig', array(
            'burger' => $burger,
            'formBurger' => $form->createView(),
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

        return $this->render('admin/burger/show.html.twig', array(
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
    public function editAction(Request $request, Burger $burger, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $image = $burger->getThumbnail();
        $deleteForm = $this->createDeleteForm($burger);
        $editForm = $this->createForm('AppBundle\Form\BurgerType', $burger);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            
             $imagesNew = $em->find('AppBundle:Burger', $id);
            $f = $this->createForm(BurgerType::class, $imagesNew);
            
            $f->handleRequest($request);
            
             if ($imagesNew->getThumbnail() == null) { //on change pas d'images
                $imagesNew->setThumbnail($image); //On garde celle déja uploader
            }else{ //sinon on upload a nouveau
                 $nomDuFichier = md5(uniqid()) . '.' . $imagesNew->getThumbnail()->getClientOriginalExtension();
                $imagesNew->getThumbnail()->move('uploads/img', $nomDuFichier);
                $imagesNew->setThumbnail($nomDuFichier);
            }
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('burger_edit', array('id' => $burger->getId()));
        }

        return $this->render('admin/burger/edit.html.twig', array(
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
     * @return Form The form
     */
    private function createDeleteForm(Burger $burger)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('burger_delete', array('id' => $burger->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    
     /**
     *
     * @Method({"GET", "POST"})
     * @Route("/ajax/snippet/image/send", name="ajax_snippet_image_send")
     */
    public function ajaxSnippetImageSendAction(Request $request)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

        $burger = new Burger();
        $media = $request->files->get('file');

        $burger->setThumbnail($media);
        $burger->setThumbnail($media->getClientOriginalName());
        $burger->upload();
        $em->persist($burger);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }

    
}
