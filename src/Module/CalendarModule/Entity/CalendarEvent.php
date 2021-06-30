<?php

declare(strict_types=1);

namespace Lea\Module\CalendarModule\Entity;

use Lea\Core\Type\Date;
use Lea\Core\Entity\Entity;
use Lea\Module\CalendarModule\Entity\Alert;

class CalendarEvent extends Entity
{
    /**
     * @var int
     */
    private $user_id;
    
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $status;
    
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $date_full;

    /**
     * @var Date
     */
    private $date_start;

    /**
     * @var Date
     */
    private $date_end;

    /**
     * @var string
     */
    private $time_start;

    /**
     * @var string
     */
    private $time_end;

    /**
     * @var bool
     */
    private $periodicity;
    /**
     * @var string
     */
    private $periodicity_value;

    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $color;

    /**
     * @var int
     */
    private $contractor_id;

    /**
     * @var iterable<Alert>
     */
    private $alerts;

    /**
     * @var string
     * @reference Lea\Core\Security\Entity\User
     */
    private $employees;


    /**
     * Get the value of type
     *
     * @return  string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param  string  $type
     *
     * @return  self
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return  string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  string  $title
     *
     * @return  self
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of date_start
     *
     * @return  date
     */
    public function getDateFull()
    {
        return $this->date_full;
    }

    /**
     * Set the value of date_start
     *
     * @param  string  $date_start
     *
     * @return  self
     */
    public function setDateFull($date_full)
    {
        $this->date_full = $date_full;

        return $this;
    }

    /**
     * Get the value of date_start
     *
     * @return  date
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set the value of date_start
     *
     * @param  date  $date_start
     *
     * @return  self
     */
    public function setDateStart(Date $date_start)
    {
        $this->date_start = $date_start;

        return $this;
    }

    /**
     * Get the value of date_end
     *
     * @return  date
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * Set the value of date_end
     *
     * @param  Date  $date_end
     *
     * @return  self
     */
    public function setDateEnd(Date $date_end)
    {
        $this->date_end = $date_end;

        return $this;
    }

    /**
     * Get the value of time_start
     *
     * @return  string
     */
    public function getTimeStart()
    {
        return $this->time_start;
    }

    /**
     * Set the value of time_start
     *
     * @param  string  $time_start
     *
     * @return  self
     */
    public function setTimeStart(string $time_start)
    {
        $this->time_start = $time_start;

        return $this;
    }

    /**
     * Get the value of periodicity
     *
     * @return  bool
     */
    public function getPeriodicity()
    {
        return $this->periodicity;
    }

    /**
     * Set the value of periodicity
     *
     * @param  bool  $periodicity
     *
     * @return  self
     */
    public function setPeriodicity($periodicity)
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    /**
     * Get the value of periodicity_value
     *
     * @return  string
     */
    public function getPeriodicityValue()
    {
        return $this->periodicity_value;
    }

    /**
     * Set the value of periodicity_value
     *
     * @param  string  $periodicity_value
     *
     * @return  self
     */
    public function setPeriodicityValue(string $periodicity_value)
    {
        $this->periodicity_value = $periodicity_value;

        return $this;
    }

    /**
     * Get the value of location
     *
     * @return  string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the value of location
     *
     * @param  string  $location
     *
     * @return  self
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param  string  $description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of color
     *
     * @return  string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @param  string  $color
     *
     * @return  self
     */
    public function setColor(string $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get the value of alerts
     *
     * @return  iterable<Alerts>
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * Set the value of alerts
     *
     * @param  iterable<Alerts>  $alerts
     *
     * @return  self
     */
    public function setAlerts($alerts)
    {
        $this->alerts = $alerts;

        return $this;
    }

    /**
     * Get the value of time_end
     *
     * @return  string
     */
    public function getTimeEnd()
    {
        return $this->time_end;
    }

    /**
     * Set the value of time_end
     *
     * @param  string  $time_end
     *
     * @return  self
     */
    public function setTimeEnd(string $time_end)
    {
        $this->time_end = $time_end;

        return $this;
    }

    /**
     * Get the value of employees
     *
     * @return  string
     */ 
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Set the value of employees
     *
     * @param  string  $employees
     *
     * @return  self
     */ 
    public function setEmployees(string $employees)
    {
        $this->employees = $employees;

        return $this;
    }

    /**
     * Get the value of contractor_id
     *
     * @return  int
     */ 
    public function getContractorId()
    {
        return $this->contractor_id;
    }

    /**
     * Set the value of contractor_id
     *
     * @param  int  $contractor
     *
     * @return  self
     */ 
    public function setContractorId(int $contractor_id)
    {
        $this->contractor_id = $contractor_id;

        return $this;
    }

    /**
     * Get the value of contractor
     *
     * @return  int
     */ 
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the value of contractor
     *
     * @param  int  $contractor
     *
     * @return  self
     */ 
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of status
     *
     * @return  string
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param  string  $status
     *
     * @return  self
     */ 
    public function setStatus(string $status)
    {
        $this->status = $status;

        return $this;
    }
}
