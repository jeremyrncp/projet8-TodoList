<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\Enum;

class RolesEnum
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';
    public const ROLES = [
        'Administrateur' => self::ROLE_ADMIN,
        'Utilisateur' => self::ROLE_USER
    ];
}
