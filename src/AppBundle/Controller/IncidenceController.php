<?php
// src/AppBundle/Controller/IncidenceController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\User;
use AppBundle\Entity\Student;
use AppBundle\Entity\Incidence;
use Symfony\Component\Validator\Constraints\Length as LengthConstraint;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class IncidenceController extends Controller
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
     *  description="This method create a incidence from the user(Student)",
     *  requirements={
     *      {
     *          "name"="description",
     *          "dataType"="String",
     *          "description"="description of the inicidence"
     *      },
     *      {
     *          "name"="file_name",
     *          "dataType"="String",
     *          "description"="file_name with the picture"
     *      },
     *  },
     * )
     */
    public function createAction(Request $request)
    {

        $description=$request->request->get('description');
        $file_name=$request->request->get('file_name');

        if (!$this->validateLenghtInput($description)){
            return $this->returnjson(false,'Descripcion no es valida.');
        }
        try {
            $user=$this->get('security.token_storage')->getToken()->getUser();
            $incidence = new Incidence();

            $incidence->setStudent($user);
            $incidence->setDescription($description);
            $incidence->setFileName($file_name);
            $user->addIncidence($incidence);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($incidence);
            $em->persist($user);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.');
        }
        return $this->returnjson(true,'La incidencia se ha creado correctamente.');
    }


    /**
     * @ApiDoc(
     *  description="This method update the state of a incidence.",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "description"="ID of the inicidence"
     *      },
     *      {
     *          "name"="state",
     *          "dataType"="String",
     *          "description"="new state of the incidence."
     *      },
     *  },
     * )
     */
    public function updateStateAction(Request $request)
    {

        $id=$request->request->get('id');
        $state=$request->request->get('state');

        //validate state.
        if (!$this->validateState($state)){
            return $this->returnjson(false,'El nuevo estado no es valido.');
        }
        try {
            $incidence = $this->getDoctrine()->getRepository('AppBundle:Incidence')->find($id);

            $incidence->setStatus($state);
            $em = $this->getDoctrine()->getManager();
            // tells Doctrine you want to (eventually) save the Product (no queries is done)
            $em->persist($incidence);
            // actually executes the queries (i.e. the INSERT query)
            //Doctrine looks through all of the objects that it's managing to see if they need to be persisted to the database.
            $em->flush();
        } catch (\Exception $pdo_ex) {
            return $this->returnjson(false,'SQL exception.');
        }
        return $this->returnjson(true,'La incidencia se ha actualizado correctamente.');
    }



    public function validateLenghtInput($input,$min=1,$max=100)
    {

        $lengthConstraint = new LengthConstraint(array(
        'min'        => $min,
        'max'        => $max,
        'minMessage' => 'Lengh should be >'.$min.'.',
        'maxMessage' => 'Lengh should be <'.$max.'.'));
        $errors = $this->get('validator')->validate(
            $input,
            $lengthConstraint
        );
        if ($errors==""){//if it is empty
            return true;
        }else{
            return false;
        }
    }

    public function validateState($state)
    {
        if($state=="OPEN" or $state=="IN PROGRESS" or $state=="DONE"){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @ApiDoc(
     *  description="get list of incident of a user(Student)",
     * )
     */
    public function getAction(Request $request)
    {
        $user=$this->get('security.token_storage')->getToken()->getUser();
        $incidences=$user->getIncidences()->getValues();

        $output=array();
        for ($i = 0; $i < count($incidences); $i++) {
            array_push($output,$incidences[$i]->getJSON()
            );
        }


        return $this->returnjson(true,'List of inicidences',$output);
    }

}