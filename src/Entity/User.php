<?php
namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, CsvLineInterface
{
    private $username;
    private $showname;
    private $status;
    private $password;
    private $role;
    
    public function __construct()
    {
        $this->role = 'ROLE_USER';
    }

    public function set($username, $showname, $status, $password)
    {
        $this->username = $username;
        $this->showname = $showname;
        $this->status = $status;
        $this->password = $password;
    }
    
    public function setFromArray(array $row)
    {
        $this->username = $row[0];
        $this->showname = $row[1];
        $this->status = $row[2];
        $this->password = $row[3];
    }
    
    public function getUsername()
    {
        return $this->username;
    }
	
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function getShowname()
    {
        return $this->showname;
    }
	
    public function setShowname($showname)
    {
        $this->showname = $showname;
    }
    
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role = null)
    {
        $this->role = $role;
    }

    public function getRoles()
    {
        return [$this->getRole()];
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function eraseCredentials()
    {
        return null;
    }
    
    public function isEnabled()
    {
        return ($this->status === 'Active');
    }
    
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->role !== $user->getRole()) {
            return false;
        }

        return true;
    }
    
    public function convertToCsvArray()
    {   
        $line = array();
        array_push($line,$this->username);
        array_push($line,$this->showname);
        array_push($line,$this->status);
        array_push($line,$this->password);
        return $line;
    }
}
?>