<?php
// src/AppBundle/Entity/Rent.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Rent
{
    private $id;
    private $status_paid;
    private $price;
    private $date;
    private $file_receipt;
    private $date_paid;
    private $student;
    private $card_holder;
    private $card_number;


    public function __construct()
    {
        $this->status_paid = 0;
        $this->read_by_student = 0;
        $this->card_holder = null;#Generate in the pay method
        $this->card_number = null;#Generate in the pay method
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Incidence
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Incidence
     */
    public function setStudent(\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }


    /**
    * @return JSON format of the rent
    */
    public function getJSON()
    {
        $output=array(
            'id'=>$this->getId(),
            'status_paid' => $this->getStatusPaid(),
            'price'=>$this->getPrice(),
            'date'=>$this->getDate(),
            'month'=>date_format($this->getDate(),"F"),
            'year'=>date_format($this->getDate(),"Y"),
            'file_receipt'=>$this->getFileReceipt(),
            'date_paid'=>$this->getDatePaid(),
            'card_number'=>$this->getCardNumber(),
            'card_holder'=>$this->getCardHolder(),
            'Student'=>$this->getStudent()->getUsername(),

        );
        return $output;
    }


    /**
     * Set statusPaid
     *
     * @param boolean $statusPaid
     *
     * @return Rent
     */
    public function setStatusPaid($statusPaid)
    {
        $this->status_paid = $statusPaid;

        return $this;
    }

    /**
     * Get statusPaid
     *
     * @return boolean
     */
    public function getStatusPaid()
    {
        return $this->status_paid;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Rent
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set fileReceipt
     *
     * @param string $fileReceipt
     *
     * @return Rent
     */
    public function setFileReceipt($fileReceipt)
    {
        $this->file_receipt = $fileReceipt;

        return $this;
    }

    /**
     * Get fileReceipt
     *
     * @return string
     */
    public function getFileReceipt()
    {
        return $this->file_receipt;
    }

    /**
     * Set datePaid
     *
     * @param \DateTime $datePaid
     *
     * @return Rent
     */
    public function setDatePaid($datePaid)
    {
        $this->date_paid = $datePaid;

        return $this;
    }

    /**
     * Get datePaid
     *
     * @return \DateTime
     */
    public function getDatePaid()
    {
        return $this->date_paid;
    }

    /**
     * Set cardHolder
     *
     * @param string $cardHolder
     *
     * @return Rent
     */
    public function setCardHolder($cardHolder)
    {
        $this->card_holder = $cardHolder;

        return $this;
    }

    /**
     * Get cardHolder
     *
     * @return string
     */
    public function getCardHolder()
    {
        return $this->card_holder;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     *
     * @return Rent
     */
    public function setCardNumber($cardNumber)
    {
        $this->card_number = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->card_number;
    }
}
