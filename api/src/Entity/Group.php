<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Group
 *
 * @ORM\Table(name="user_group")
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Group
{
    /**
     * @var string
     * @ORM\Column(name="id", type="string",length=36)
     * @ORM\Id
     */
    private string $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private string $name;

    private User $owner;

    /**
     * @var datetime
     * @ORM\Column(name="createdAt", type="datetime")
     **/
    private \DateTime $createdAt;

    /**
     * @var datetime
     * @ORM\Column(name="updatedAt", type="datetime")
     **/
    private \DateTime $updatedAt;

    private Collection $users;

    public function __construct(string $name, User $owner)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->owner = $owner;
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
        $this->users = new ArrayCollection([$owner]);
        $owner->addGroup($this);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
    }

    public function removeUser(User $user): void
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
    }

    public function containsUser(User $user): bool
    {
        return $this->users->contains($user);
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->owner->getId() === $user->getId();
    }

}