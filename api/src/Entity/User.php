<?php


namespace App\Entity;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;


/**
 * Usuario
 *
 * @ORM\Table(name="symfony_db.user")
 * @ORM\Entity(repositoryClass="App\Repository\Core\UsuarioRepository")
 * @ORM\HasLifecycleCallbacks()
 */

/**
 * @method string getUserIdentifier()
 */
class User implements UserInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string",length=36)
     * @ORM\Id
     */
    private string $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    private string $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     */
    private string $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=true)
     */
    private ?string $password;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private ?string $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, nullable=true)
     */
    private ?string $token;

    /**
     * @var string
     *
     * @ORM\Column(name="resetPasswordToken", type="string", length=100, nullable=true)
     */
    private ?string $resetPasswordToken;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private bool $active;

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

    /**
     * User constructor.
     * @param string $name
     * @param string $email
     */
    public function __construct(string $name, string $email)
    {
        $this->id= Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->setEmail($email);
        $this->password = null;
        $this->avatar = null;
        $this->token = \sha1(\uniqid());
        $this->resetPasswordToken = null;
        $this->active = false;
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        if(!\filter_var($email,FILTER_VALIDATE_EMAIL)){
            throw new \LogicException('Invalid email');
        }
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string|null
     */
    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    /**
     * @param string|null $resetPasswordToken
     */
    public function setResetPasswordToken(?string $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }


    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getSalt(): void
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}