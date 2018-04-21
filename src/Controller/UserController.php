<?php
/**
 * Created by PhpStorm.
 * User: wesleyhjpr
 * Date: 21/04/2018
 * Time: 10:53
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;


class UserController extends Controller
{
    /**
     * @FOSRest\Get("/users")
     */
    public function getAction()
    {
        $restresult = $this->getDoctrine()->getRepository(User::class)->findAll();
        if ($restresult == null) {
            return new JsonResponse("there are no users exist", Response::HTTP_NOT_FOUND,[]);
        }
        return new JsonResponse($restresult, Response::HTTP_OK , []);
    }

    /**
     * @FOSRest\Get("/users/{id}")
     */
    public function idAction($id)
    {
        $singleresult = $this->getDoctrine()->getRepository(User::class)->find($id);
        if ($singleresult === null) {
            return new JsonResponse("user not found", Response::HTTP_NOT_FOUND,[]);
        }
        return new JsonResponse($singleresult, Response::HTTP_OK , []);
    }
    /**
     * @FOSRest\Put("/users/{id}")
     */
    public function updateAction($id,Request $request)
    {
        $file = $request->getContent();
        $fileDecode = json_decode($file);
        $name = $fileDecode->name;
        $email = $fileDecode->email;

        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (empty($user)) {
            return new JsonResponse("user not found", Response::HTTP_NOT_FOUND,[]);
        }
        elseif(!empty($name) && !empty($email)){
            $user->setName($name);
            $user->setEmail($email);
            $sn->flush();
            return new JsonResponse("User Updated Successfully", Response::HTTP_CREATED,[]);
        }
        elseif(empty($name) && !empty($email)){
            $user->setEmail($email);
            $sn->flush();
            return new JsonResponse("email Updated Successfully", Response::HTTP_CREATED,[]);
        }
        elseif(!empty($name) && empty($email)){
            $user->setName($name);
            $sn->flush();
            return new JsonResponse("User Name Updated Successfully", Response::HTTP_CREATED,[]);
        }
        else return new JsonResponse("User name or email cannot be empty", Response::HTTP_NOT_ACCEPTABLE,[]);
    }
    /**
     * @FOSRest\Post("/users")
     */
    public function postAction(Request $request)
    {
        $file = $request->getContent();
        $fileDecode = json_decode($file);
        $name = $fileDecode->name;
        $email = $fileDecode->email;
        $data = new User;

        if(empty($name) || empty($email))
        {
            return new JsonResponse("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE,[]);
        }
        $data->setName($name);
        $data->setEmail($email);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
        return new JsonResponse("User Added Successfully", Response::HTTP_CREATED,[]);
    }
    /**
     * @FOSRest\Delete("/users/{id}")
     */
    public function deleteAction($id)
    {
        $data = new User;
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (empty($user)) {
            return new JsonResponse("user not found", Response::HTTP_NOT_FOUND,[]);
        }
        else {
            $sn->remove($user);
            $sn->flush();
        }
        return new JsonResponse("deleted successfully", Response::HTTP_NO_CONTENT,[]);
    }
}