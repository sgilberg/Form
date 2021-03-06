<?php
namespace Lrotherfield\Component\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Lrotherfield\Component\Form\DataTransformer\EntityToIdentifierTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class HiddenEntityType
 *
 * Render an entity as a hidden field using the identifier field "id"
 * transformed using the Entity to Int transformer
 *
 * @package Lrotherfield\Bundle\TestBundle\Form\Type
 * @author Luke Rotherfield <luke@lrotherfield.com>
 */
class HiddenEntityType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Add the data transformer to the field setting the entity repository
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityTransformer = new $options['transformer']($this->om);
        $entityTransformer->setEntityRepository($options['class']);
        $builder->addModelTransformer($entityTransformer);
    }

    /**
     * Require the entity repository option to be set on the field
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
               'transformer' => 'Lrotherfield\Component\Form\DataTransformer\EntityToIdentifierTransformer'
            ));
        $resolver->setRequired(
            array(
                "class"
            )
        );
    }

    /**
     * Require the entity repository option to be set on the field
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * Set the parent form type to hidden
     * @return string
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hidden_entity';
    }
}
