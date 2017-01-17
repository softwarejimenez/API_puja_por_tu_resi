<?php
// src/AppBundle/Controller/RoomController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\User;
use AppBundle\Entity\Student;
use AppBundle\Entity\Room;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class RoomController extends Controller
{

    public function returnjson($success,$message,$data=null)
    {
        $response = new JsonResponse();
        if (is_null($data)){
            $data=array();
        }
        $response->setData(array(
            'success' => $success,
            'message' => $message,
            'data'=> $data,
        ));
        return $response;
    }


    /**
     * @ApiDoc(
     *  description="This method create a room by the user (College)",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="String",
     *          "description"="Name of the room by the college."
     *      },
     *      {
     *          "name"="price",
     *          "dataType"="float",
     *          "description"="Price of the room per month."
     *      },
     *      {
     *          "name"="date_start_school",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) starts to live and pay."
     *      },
     *      {
     *          "name"="date_end_school",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) stops to live and pay."
     *      },
     *      {
     *          "name"="date_start_bid",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) can start to bid."
     *      },
     *      {
     *          "name"="date_end_bid",
     *          "dataType"="datetime",
     *          "description"="Date when the user (Student) can not bid anymore."
     *      },
     *      {
     *          "name"="floor",
     *          "dataType"="integer",
     *          "description"="floor of the room."
     *      },
     *      {
     *          "name"="size",
     *          "dataType"="integer",
     *          "description"="size of the room in m²."
     *      },
     *      {
     *          "name"="picture1",
     *          "dataType"="File",
     *          "description"="Image File of the room."
     *      },
     *      {
     *          "name"="picture2",
     *          "dataType"="File",
     *          "description"="Image File of the room."
     *      },
     *      {
     *          "name"="picture3",
     *          "dataType"="File",
     *          "description"="Image File of the room."
     *      },
     *      {
     *          "name"="tv",
     *          "dataType"="boolean",
     *          "description"="True if the room has a tv."
     *      },
     *      {
     *          "name"="bath",
     *          "dataType"="boolean",
     *          "description"="True if the room has bath."
     *      },
     *      {
     *          "name"="desk",
     *          "dataType"="boolean",
     *          "description"="True if the room has a desk."
     *      },
     *      {
     *          "name"="wardrove",
     *          "dataType"="boolean",
     *          "description"="True if the room has a wardrove."
     *      },
     *  },
     * )
     */
    public function createAction(Request $request)
    {
        $name=$request->request->get('name');
        $price=$request->request->get('price');
        $date_start_school=date_create($request->request->get('date_start_school'));
        $date_end_school=date_create($request->request->get('date_end_school'));
        $date_start_bid=date_create($request->request->get('date_start_bid'));
        $date_end_bid=date_create($request->request->get('date_end_bid'));
        $floor=$request->request->get('floor');
        $size=$request->request->get('size');
        $picture1=$request->files->get('picture1');
        $picture2=$request->files->get('picture2');
        $picture3=$request->files->get('picture3');
        $tv=$request->request->get('tv');
        $bath=$request->request->get('bath');
        $desk=$request->request->get('desk');
        $wardrove=$request->request->get('wardrove');

        //TODO validate everything
        if (!$this->get('app.validate')->validateImageFile($this->get('validator'),$picture1)){
            return $this->returnjson(false,'Archivo no es valido.');
        }
        $filename1=md5(uniqid()).'.'.$picture1->getClientOriginalExtension();
        $picture1->move($this->container->getParameter('storageFiles'),$filename1);
        $filename2=md5(uniqid()).'.'.$picture2->getClientOriginalExtension();
        $picture2->move($this->container->getParameter('storageFiles'),$filename2);
        $filename3=md5(uniqid()).'.'.$picture3->getClientOriginalExtension();
        $picture3->move($this->container->getParameter('storageFiles'),$filename3);

        try {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            if ($user->getRoles()[0]=="ROLE_COLLEGE"){
                $room = new Room();
                $room->setCollege($user);
                $room->setName($name);
                $room->setPrice($price);
                $room->setDateStartSchool($date_start_school);
                $room->setDateEndSchool($date_end_school);
                $room->setDateStartBid($date_start_bid);
                $room->setDateEndBid($date_end_bid);
                $room->setFloor($floor);
                $room->setSize($size);
                $room->setPicture1($filename1);
                $room->setPicture2($filename2);
                $room->setPicture3($filename3);
                $room->setTv($tv);
                $room->setBath($bath);
                $room->setDesk($desk);
                $room->setWardrove($wardrove);

                $user->addRoom($room);

                $em = $this->getDoctrine()->getManager();
                // tells Doctrine you want to (eventually) save the Product (no queries is done)
                $em->persist($room);
                $em->persist($user);
                // actually executes the queries (i.e. the INSERT query)
                //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
                $em->flush();
            }else{
                return $this->returnjson(False,'The user Student cannot create a room.');
            }
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.'.$pdo_ex);
        }
        return $this->returnjson(true,'La habitacion se ha creado correctamente.');
    }
}
