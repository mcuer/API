<?php
namespace AppBundle\Controller;
use Symfony\Component\Console\Output\ConsoleOutput;
use AppBundle\Entity\message;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class messageController extends Controller
{
    /**
     * @Route("/message/{id}", name="message_show")
     * @Method({"GET"})
     */

    public function showAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $id = $request->get('id');
        $user = $em->getRepository('AppBundle:user')->find($id);
        $message = $em->getRepository('AppBundle:message')->findBy(['creator' => $user]);
        
        $user = $user->getFollows();
        foreach($user as $valeur)
        {
            foreach($valeur->getFollowed()->getMessages() as $valeur)
            {
                array_push($message , $valeur);
            }
        }
        


        $data = $this->get('jms_serializer')->serialize($message, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
     }

    /**
     * @Route("/message", name="message_create")
     * @Method({"POST"})
     */

    public function createAction(Request $request)
        {
            $data = $request->getContent();
            $json = json_decode($data);
            $message = new Message();
            $message->
                setText($json->text);
            $em = $this->getDoctrine()->getManager();
            $creator = $em->getRepository('AppBundle:user')->find($json->creator_id);
            $message->
                setCreator($creator);
            $em->persist($message);
            $em->flush();
            return new Response($data, Response::HTTP_CREATED);
        }

    /**
     * @Route ("/message/{id}", name="message_delete")
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
        $message = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:message')->Find($id);
        
        if($message != null)
        {
            $em->remove($message);
            $em->flush();
        }
        return new Response(Response::HTTP_OK);
    }

}