<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class that add model_transformer extension to form field property
 */
class ModelTransformerExtension extends AbstractTypeExtension {

    public static function getExtendedTypes(): iterable {
        return [FormType::class];
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        if (isset($options['model_transformer'])) {
            $builder->addModelTransformer($options['model_transformer']);
        }
    }

    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array('model_transformer' => null));
    }
}