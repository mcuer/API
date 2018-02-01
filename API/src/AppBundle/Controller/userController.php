<?php
namespace AppBundle\Controller;
use AppBundle\Entity\user;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class userController extends Controller
{
    /**
     * @Route("/user/{id}", name="user_show")
     * @Method({"GET"})
     */
    public function showAction(user $user)
    {
        $data = $this->get('jms_serializer')->serialize($user, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("/users", name="user_list")
     * @Method({"GET"})
     */
    public function showAllAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:user')->findAll();
        $data = $this->get('jms_serializer')->serialize($users, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/user", name="user_create")
     * @Method({"POST"})
     */

    public function createAction(Request $request)
        {
            $data = $request->getContent();
            $user = $this->get('jms_serializer')->deserialize($data, 'AppBundle\Entity\user', 'json');
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new Response($data, Response::HTTP_CREATED);

        }

    /**
     * @Route ("/user/{id}", name="user_delete")
     * @Method({"DELETE"})
     */

    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        if(!$id)
        {
            throw $this->createNotFoundException('No ID found');
        }
        $user = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:user')->Find($id);
        
        if($user != null)
        {
            $em->remove($user);
            $em->flush();
        }
        return new Response(Response::HTTP_OK);
    }

}