<?php

namespace AppBundle\Entity;

use AppBundle\Enum\RolesEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table("user")
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     */
    private $email;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Task", mappedBy="user")
     */
    private $tasks;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];


    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }


    /**
     * @return ArrayCollection
     */
    public function getTasks(): ArrayCollection
    {
        return $this->tasks;
    }

    /**
     * @param Task $task
     */
    public function addTask(Task $task): void
    {
        if (!$this->tasks->contains($task)) {
            $task->setUser($this);
            $this->tasks->add($task);
        }
    }

    public function removeTask(Task $task)
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function addRole(string $role)
    {
        if (in_array($role, RolesEnum::ROLES)) {
            $this->roles[] = $role;
        }
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function hasRole(string $role = RolesEnum::ROLE_USER)
    {
        return in_array($role, $this->roles);
    }

    public function eraseCredentials()
    {
    }
}
