<?php
/**
 * Crée par Jérémy Gaultier <contact@webmezenc.com>
 * Tous droits réservés
 */

namespace AppBundle\Form\Handler;

use AppBundle\OptionsResolver\OptionsResolverInterface;
use AppBundle\Exception\MissingNecessaryOperation;


class Handler
{
    /**
     * @var OptionsResolverInterface
     */
    protected $optionsResolver;

    /**
     * @param OptionsResolverInterface $optionsResolver
     */
    public function setOptionsResolver(OptionsResolverInterface $optionsResolver)
    {
        $this->optionsResolver = $optionsResolver;
    }

    /**
     * @return array
     * @throws MissingNecessaryOperation
     */
    protected function getOptions(): array
    {
        if ($this->optionsResolver === null) {
            throw new MissingNecessaryOperation('Options must be defined with setOptionsResolver');
        }

        return $this->optionsResolver->getOptions();
    }
}
