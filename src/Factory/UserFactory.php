<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<User>
 *
 * @method static User|Proxy               createOne(array $attributes = [])
 * @method static array<User>|array<Proxy> createMany(int $number, array|callable $attributes = [])
 * @method static User|Proxy find(object|array $criteria)//|mixed
 * @method static User|Proxy                     findOrCreate(array $attributes)
 * @method static User|Proxy                     first(string $sortedField = 'id')
 * @method static User|Proxy                     last(string $sortedField = 'id')
 * @method static User|Proxy                     random(array $attributes = [])
 * @method static User|Proxy                     randomOrCreate(array $attributes = [])
 * @method static array<User>|array<Proxy>       all()
 * @method static array<User>|array<Proxy>       findBy(array $attributes)
 * @method static array<User>|array<Proxy>       randomSet(int $number, array $attributes = [])
 * @method static array<User>|array<Proxy>       randomRange(int $min, int $max, array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method        User|Proxy                     create(array|callable $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    /* public function __construct()
    {
        parent::__construct();

        //to_do_task inject services if required (...#factories-as-services)
    } */

    /**
     * getDefaults function.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    protected function getDefaults(): array
    {
        return [
            // to_do_task add your default values here (...#model-factories)
            'email' => self::faker()->email(),
            'roles' => [],
            'password' => self::faker()->password(12, 20), // ->text(),
            'username' => self::faker()->userName(),
            'points' => /* (float) */ self::faker()->randomFloat(0, 0, 19),
            'createdAt' => self::faker()->dateTime(),
            'updatedAt' => self::faker()->dateTime(),
            'fullName' => self::faker()->text(),
            // 'isVerified' => self::faker()->boolean(),
            // 'isDeleted' => self::faker()->boolean(),
            // 'isExpired' => self::faker()->boolean(),
        ];
    }

    #[\Override]
    protected function initialize(): self
    {
        // see ...#initialization
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }

    #[\Override]
    protected static function getClass(): string
    {
        return User::class;
    }
}
