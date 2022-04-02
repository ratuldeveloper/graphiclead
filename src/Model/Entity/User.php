<?php
declare(strict_types=1);

namespace App\Model\Entity;
use Cake\ORM\Entity;
use SwaggerBake\Lib\Annotation\SwagEntityAttribute;
use Authentication\PasswordHasher\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property \App\Model\Entity\User $user
 */
class User extends Entity {

    protected $_accessiable = [
        'first_name' => true,
        'last_name' => true


    ];
    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }

}


?>