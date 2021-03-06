<?php
// src/AppBundle/Entity/Student.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

class Student implements AdvancedUserInterface, \Serializable
{
    private $username;
    private $password;
    private $email;
    private $isActive;
    private $name;
    private $creationDate;
    private $incidences;
    private $messages;
    private $bids;
    private $rents;
    private $agreements;


    public function getUsername()
    {
        return $this->username;
    }

    public function __construct()
    {
        $this->isActive = true;
        $this->creationDate=date_create('now');
        $this->incidences = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->bids = new ArrayCollection();
        $this->rents = new ArrayCollection();
        $this->agreements = new ArrayCollection();

        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    public function isAccountNonExpired()
   {
       return true;
   }

   public function isAccountNonLocked()
   {
       return true;
   }

   public function isCredentialsNonExpired()
   {
       return true;
   }

   public function isEnabled()
   {
       return $this->isActive;
   }


    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_STUDENT');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->username,
            $this->password,
            $this->isActive,
            $this->name,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
    * @return JSON format of the user student
    */
    public function getJSON()
    {
        $output=array(
            'username'=>$this->getUsername(),
            'name' => $this->getName(),
            'email'=>$this->getEmail(),
            'creationDate'=>$this->getCreationDate(),
            'point'=>$this->get_point(),
            'ROLE'=>$this->getRoles(),
        );
        return $output;
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->username,
            $this->password,
            $this->isActive,
            $this->name,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setusername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }



    /**
    * @param string  $username,$password,$email
    *
    * @return User
    */
   public function set( $username,$password,$email,$name)
   {
       $this->username = $username;
       $this->password = $password;
       $this->email = $email;
       $this->isActive = true;
       $this->name=$name;
       return $this;
   }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Student
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Student
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Add incidence
     *
     * @param \AppBundle\Entity\Incidence $incidence
     *
     * @return Student
     */
    public function addIncidence(\AppBundle\Entity\Incidence $incidence)
    {
        $this->incidences[] = $incidence;

        return $this;
    }

    /**
     * Remove incidence
     *
     * @param \AppBundle\Entity\Incidence $incidence
     */
    public function removeIncidence(\AppBundle\Entity\Incidence $incidence)
    {
        $this->incidences->removeElement($incidence);
    }

    /**
     * Get incidences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncidences()
    {
        return $this->incidences;
    }

    /**
     * Add message
     *
     * @param \AppBundle\Entity\Message $message
     *
     * @return Student
     */
    public function addMessage(\AppBundle\Entity\Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\Message $message
     */
    public function removeMessage(\AppBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add rent
     *
     * @param \AppBundle\Entity\Rent $rent
     *
     * @return Student
     */
    public function addRent(\AppBundle\Entity\Rent $rent)
    {
        $this->rents[] = $rent;

        return $this;
    }

    /**
     * Remove rent
     *
     * @param \AppBundle\Entity\Rent $rent
     */
    public function removeRent(\AppBundle\Entity\Rent $rent)
    {
        $this->rents->removeElement($rent);
    }

    /**
     * Get rents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRents()
    {
        return $this->rents;
    }

    /**
     * Add bid
     *
     * @param \AppBundle\Entity\Bid $bid
     *
     * @return Student
     */
    public function addBid(\AppBundle\Entity\Bid $bid)
    {
        $this->bids[] = $bid;

        return $this;
    }

    /**
     * Remove bid
     *
     * @param \AppBundle\Entity\Bid $bid
     */
    public function removeBid(\AppBundle\Entity\Bid $bid)
    {
        $this->bids->removeElement($bid);
    }

    /**
     * Get bids
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBids()
    {
        return $this->bids;
    }


    /**
     * Calculate the number of point of the user. It is the number of days from the register.
     */
    public function get_point(){
        $now =  date_create('now');
        $interval = date_diff($this->getCreationDate(),$now);
        return $interval->format('%a');
    }

    /**
     * Add agreement
     *
     * @param \AppBundle\Entity\Agreement $agreement
     *
     * @return Student
     */
    public function addAgreement(\AppBundle\Entity\Agreement $agreement)
    {
        $this->agreements[] = $agreement;

        return $this;
    }

    /**
     * Remove agreement
     *
     * @param \AppBundle\Entity\Agreement $agreement
     */
    public function removeAgreement(\AppBundle\Entity\Agreement $agreement)
    {
        $this->agreements->removeElement($agreement);
    }

    /**
     * Get agreements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgreements()
    {
        return $this->agreements;
    }

    /**
     * Get current agreement or the future agreement.
     *
     * @return \AppBundle\Entity\Agreement $agreement or null
     */
    public function getCurrentAgreement()
    {
        $list_agreement=$this->getAgreements()->getValues();
        $today=date_create('now')->format('Y-m-d');//year month and day (not hour and minute)
        for ($i = 0; $i < count($list_agreement); $i++) {
            if ($list_agreement[$i]->getDateSigned()->format('Y-m-d')<= $today &&
             $list_agreement[$i]->getDateEndSchool()->format('Y-m-d')>= $today){//the current date is ina contract
                return $list_agreement[$i];
            }
        }
        return null;
    }

    /**
     * check if a student has an agreement between 2 dates
     *
     * boolean. Return true if the student is available
     */
    public function checkAvailability($date_start_school,$date_end_school)
    {
        $list_agreement=$this->getAgreements()->getValues();
        $today=date_create('now')->format('Y-m-d');//year month and day (not hour and minute)
        for ($i = 0; $i < count($list_agreement); $i++) {
            if (
                (
                    $list_agreement[$i]->getDateStartSchool()->format('Y-m-d')>= $date_start_school->format('Y-m-d') &&
                    $list_agreement[$i]->getDateStartSchool()->format('Y-m-d')<= $date_end_school->format('Y-m-d')
                 )
                ||
                (
                    $list_agreement[$i]->getDateEndSchool()->format('Y-m-d')>= $date_start_school->format('Y-m-d') &&
                    $list_agreement[$i]->getDateEndSchool()->format('Y-m-d')<= $date_end_school->format('Y-m-d')
                )
                ||
                (
                    $list_agreement[$i]->getDateEndSchool()->format('Y-m-d')>= $date_end_school->format('Y-m-d') &&
                    $list_agreement[$i]->getDateStartSchool()->format('Y-m-d')<= $date_start_school->format('Y-m-d')
                )
            ){//the current date is in a contract
                return False ;
            }
        }
        return True;
    }
}
