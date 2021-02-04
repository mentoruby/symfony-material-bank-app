<?php
namespace App\Form;

use App\Entity\FundTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;

class FundTransferType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = array();
        
        $builder
            ->add('accountId', HiddenType::class, array(
            ))
            ->add('transferType', ChoiceType::class, array(
                'label' => 'Transfer Type', 
                'attr' => $attr,
                'choices' => array('Deposit' => 'D', 'Withdraw' => 'W'),
                'constraints' => array(
                    new NotBlank(array("message" => "Please select a Transfer Type")),
                )
            ))
            ->add('currency', ChoiceType::class, array(
                'label' => 'Currency To Transfer', 
                'attr' => $attr,
                'choices' => array('HKD' => 'HKD', 'USD' => 'USD', 'CNY' => 'CNY'),
                'constraints' => array(
                    new NotBlank(array("message" => "Please select a Currency to transfer")),
                )
            ))
            ->add('amount', IntegerType::class, array(
                'label' => 'Transfer Amount', 
                'attr' => ($attr + ['placeholder' => 'Transfer Amount']),
                'constraints' => array(
                    new NotBlank(array("message" => "Please input a non-zero amount")),
                    new GreaterThan(array("value" => 0, "message" => "Amount must be greater than zero")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FundTransfer::class
        ]);
    }

    public function getName()
    {
        return 'fundtransfer';
    }
}
?>