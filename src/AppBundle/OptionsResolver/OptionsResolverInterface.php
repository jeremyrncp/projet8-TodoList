<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\OptionsResolver;

interface OptionsResolverInterface
{
    public function __construct(array $options = []);
    public function getOptions(): array;
}