<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;

interface FormHandlerInterface
{
    public function handle(FormInterface $form);
}
