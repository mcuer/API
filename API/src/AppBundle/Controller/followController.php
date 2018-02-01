<?php
namespace AppBundle\Controller;
use AppBundle\Entity\follow;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class followController extends Controller
{
    /**

     * @Route("/follow/{id}", name="follow_show")
     *  @Method({"GET"})
     */

    public function showAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id = $request->get('id');
        $follow = $em->getRepository('AppBundle:follow')->find($id);

        $data = $this->get('jms_serializer')->serialize($follow, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
     }

    /**
     * @Route("/follow", name="follow_create")
     * @Method({"POST"})
     */

    public function createAction(Request $request)
        {
            $data = $request->getContent();
            $json = json_decode($data);
            $follow = new follow();
            $em = $this->getDoctrine()->getManager();
            $follower = $em->getRepository('AppBundle:user')->find($json->follower_id);
            $followed = $em->getRepository('AppBundle:user')->find($json->followed_id);
            
            $followSearch = $em->getRepository('AppBundle:follow')->findOneBy([
                'follower' => $follower,
                'followed' => $followed
            ]);

            if (is_null($followSearch) && ($json->follower_id != $json->followed_id))
            {
                $follow->
                setFollower($follower)->
                setFollowed($followed);
                $em->persist($follow);
                $em->flush();
                return new Response($data, Response::HTTP_CREATED);
            }
            else
            {
                return new Response($data, Response::HTTP_ALREADY_REPORTED);
            }

        }

    /**
     * @Route ("/follow", name="follow_delete")
     * @Method({"DELETE"})
     */

    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->getContent();
        $json = json_decode($data);
        $follower = $em->getRepository('AppBundle:user')->find($json->follower_id);
        $followed = $em->getRepository('AppBundle:user')->find($json->followed_id);
        
        $followSearch = $em->getRepository('AppBundle:follow')->findOneBy([
            'follower' => $follower,
            'followed' => $followed
        ]);

        if (!is_null($followSearch))
        {
            $em->remove($followSearch);
            $em->flush();
            return new Response($data, Response::HTTP_CREATED);
        }
        else
        {
            return new Response($data, Response::HTTP_ALREADY_REPORTED);
        }
    }

}