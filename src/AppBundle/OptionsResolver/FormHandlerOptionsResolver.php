<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\OptionsResolver;

use Symfony\Component\OptionsResolver\OptionsResolver;

class FormHandlerOptionsResolver implements OptionsResolverInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * FormHandlerOptionsResolver constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setRequired('entity');
        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
