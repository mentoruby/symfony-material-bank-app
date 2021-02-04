<?php
namespace App\Provider;

use SplFileObject;
use App\AppLogger;
use App\Entity\User;
use App\Util\FileUtil;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginUserProvider extends BasicProvider implements UserProviderInterface
{
    public function __construct()
    {
        parent::__construct(FileUtil::getProjectDir().'/src/Data/Users.csv');
    }
    
    public function newObject()
    {
        return new User();
    }
    
    public function loadUsers()
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $list = array();
        
        foreach ($results as $row) {
            $user = new User();
            $user->setFromArray($row);
            array_push($list, $user);
        }
        
        $results = null;
        return $list;
    }
    
    public function loadUserByUsername($username)
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );

        foreach ($results as $row) {
            if(strtolower($username) == strtolower($row[0])) {
                $user = new User();
                $user->setFromArray($row);

                $results = null;
                return $user;
            }
        }

        $results = null;
        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }
    
    public function usernameExist($username)
    {
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        
        foreach ($results as $row) {
            if(strtolower($username) == strtolower($row[0])) {
                return true;
            }
        }
        
        $results = null;
        return false;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
    
    public function createUser(User $newUser, UserPasswordEncoderInterface $encoder)
    {
        $password = $encoder->encodePassword(new User(), $newUser->getPassword());
        $newUser->setPassword($password);        
        $this->appendToCsv($newUser);
    }
    
    public function updateUser($inputUser, UserPasswordEncoderInterface $encoder)
    {   
        $results = new SplFileObject($this->filePath);
        $results->setFlags(
            SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | 
            SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE
        );
        $list = array();
        
        foreach ($results as $row) {
            $user = new User();
            $user->setFromArray($row);
            
            if($user->getUsername() == $inputUser->getUsername()) {
              if($inputUser->getPassword() == null) {
                $inputUser->setPassword($user->getPassword());
              } else {
                $password = $encoder->encodePassword(new User(), $inputUser->getPassword());
                $inputUser->setPassword($password);
              }
              
              array_push($list, $inputUser);
            } else {
              array_push($list, $user);
            }
        }
        
        $this->saveToCsv($list);
        $results = null;
    }
}
?>