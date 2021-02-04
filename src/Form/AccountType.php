<?php
namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AccountType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = array();
        if(isset($options['disabled']) && $options['disabled']) {
            $attr += ['disabled' => 'disabled'];
        }
        
        $builder
            ->add('accountId', TextType::class, array(
                'label' => 'Account ID', 
                'attr' => array('readonly' => 'readonly')
            ))
            ->add('accountName', TextType::class, array(
                'label' => 'Account Name', 
                'attr' => ($attr + ['placeholder' => 'Account Name']),
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide Account Name")),
                )
            ))
            ->add('status', HiddenType::class, array(
            ))
            ->add('passportId', TextType::class, array(
                'label' => 'Passport ID', 
                'attr' => ($attr + ['placeholder' => 'Passport ID']),
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide Passport ID")),
                )
            ))
            ->add('phone', TextType::class, array(
                'label' => 'Contact Phone', 
                'attr' => ($attr + ['placeholder' => 'Contact Phone']),
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide Contact Phone")),
                )
            ))
            ->add('address', TextType::class, array(
                'label' => 'Address', 
                'attr' => ($attr + ['placeholder' => 'Address']),
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide Address")),
                )
            ))
            ->add('currency', ChoiceType::class, array(
                'label' => 'Account Currency', 
                'attr' => $attr,
                'choices' => array('HKD' => 'HKD', 'USD' => 'USD', 'CNY' => 'CNY'),
                'constraints' => array(
                    new NotBlank(array("message" => "Please select an Account Currency")),
                )
            ))
            ->add('credit', IntegerType::class, array(
                'label' => 'Account Credit', 
                'attr' => ($attr + ['placeholder' => 'Account Credit']),
                'constraints' => array(
                    new NotBlank(array("message" => "Please provide a numeric Account Credit")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class
        ]);
    }

    public function getName()
    {
        return 'account_form';
    }
}
?>