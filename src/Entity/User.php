<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="L'email que vous avez indiqué est déjà utilisé! "
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="6", minMessage="Votre mot de passe doit faire minimum 6 caractères")
     */
    private $password;
    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas entré le même mot de passe")
     */
    public $confirm_password;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */

    private $email;
    /**
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author_id")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUserName(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $roles =$this->roles;
        $roles[ ] = 'ROLE_USER' ;

        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }



public function eraseCredentials()
{}
public function getSalt()
{}

/**
 * @return Collection|Comment[]
 */
public function getComments(): Collection
{
    return $this->comments;
}

public function addComment(Comment $comment): self
{
    if (!$this->comments->contains($comment)) {
        $this->comments[] = $comment;
        $comment->setAuthorId($this);
    }

    return $this;
}

public function removeComment(Comment $comment): self
{
    if ($this->comments->contains($comment)) {
        $this->comments->removeElement($comment);
        // set the owning side to null (unless already changed)
        if ($comment->getAuthorId() === $this) {
            $comment->setAuthorId(null);
        }
    }

    return $this;
}

}
