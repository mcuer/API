<?php

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/connexion", name="connexion")
     * @Method({"POST"})
     */
    public function connexionAction(Request $request)
    {
        $data = $request->getContent();
        $json = json_decode($data);
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository('AppBundle:user')->findOneBy(['email' => $json->email]);
        if($utilisateur)
        {
            if ($utilisateur->getPassword() === $json->password)
            {
                $data = $this->get('jms_serializer')->serialize($utilisateur, 'json');
                return new Response($data,Response::HTTP_OK);
            }
            else
            {
                return new Response('',Response::HTTP_NOT_FOUND);
            }
        }
        else
        {
            return new Response('',Response::HTTP_NOT_FOUND);
        }
        
    }
}
