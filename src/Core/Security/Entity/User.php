<?php

declare(strict_types=1);

namespace Lea\Core\Security\Entity;

use Lea\Core\Entity\Entity;

class User extends Entity
{
    public static $user;
    
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     * @references Role
     */
    private $role_id;

    /**
     * @var string
     */
    private $phone;

    /** @var string */
    private $mobile_app_token;

    /**
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     *
     * @return  string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     *
     * @return  self
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of surname
     *
     * @return  string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     *
     * @param  string  $surname
     *
     * @return  self
     */
    public function setSurname(string $surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of token
     *
     * @return  string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @param  string  $token
     *
     * @return  self
     */
    public function setToken(?string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of role_id
     *
     * @return  int
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Set the value of role_id
     *
     * @param  int  $role_id
     *
     * @return  self
     */
    public function setRoleId(int $role_id)
    {
        $this->role_id = $role_id;

        return $this;
    }

    /**
     * Get the value of phone
     *
     * @return  string
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @param  string  $phone
     *
     * @return  self
     */ 
    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of mobile_app_token
     */ 
    public function getMobileAppToken()
    {
        return $this->mobile_app_token;
    }

    /**
     * Set the value of mobile_app_token
     *
     * @return  self
     */ 
    public function setMobileAppToken(?string $mobile_app_token)
    {
        $this->mobile_app_token = $mobile_app_token;

        return $this;
    }
}
