<?php
namespace App\Form;

use App\AppLogger;
use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginUserType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $disabled = false;
        if(isset($options['disabled'])) {
          $disabled = $options['disabled'];
        }
        $action = null;
        if(isset($options['action'])) {
          $action = $options['action'];
        }
          
        AppLogger::getLogger()->info('Checking LoginUserType options', ['disabled' => $disabled]);
        AppLogger::getLogger()->info('Checking LoginUserType options', ['action' => $action]);
        
        if($action == 'pwd') {
          $this->buildChangePasswordForm($builder, $options);
        } else {
          
          $attr = array();
          if($disabled) {
              $attr += ['disabled' => 'disabled'];
          }
          $passwordExtraLabel = '';
          $isPasswordRequired = false;
          $readonlyAttr = array();
          if($action == 'new') {
              $isPasswordRequired = true;
          } else {
              $passwordExtraLabel = ' (leave blank if no change)';
              $readonlyAttr += ['readonly' => 'readonly'];
          }
          
          $builder
              ->add('username', TextType::class, array(
                  'label' => 'Login ID', 
                  'attr' => ($attr + $readonlyAttr),
                  'constraints' => array(
                      new NotBlank(array("message" => "Please provide Login ID"))
                  )
              ))
              ->add('showname', TextType::class, array(
                  'label' => 'Display Name', 
                  'attr' => $attr,
                  'constraints' => array(
                      new NotBlank(array("message" => "Please provide Display Name"))
                  )
              ))
              ->add('status', ChoiceType::class, array(
                  'label' => 'Status', 
                  'attr' => $attr,
                  'choices' => array('Active' => 'Active', 'Inactive' => 'Inactive'),
                  'constraints' => array(
                      new NotBlank(array("message" => "Please select a Status"))
                  )
              ))
              ->add('password', RepeatedType::class, array(
                  'type' => PasswordType::class,
                  'invalid_message' => 'The password fields must match.',
                  'required' => $isPasswordRequired,
                  'first_options'  => array('label' => 'Password'.$passwordExtraLabel),
                  'second_options' => array('label' => 'Password to Confirm'.$passwordExtraLabel)
              ))
          ;
        }
    }
    
    private function buildChangePasswordForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', HiddenType::class, array(
            ))
            ->add('showname', HiddenType::class, array(
            ))
            ->add('status', HiddenType::class, array(
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Password to Confirm')
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

    public function getName()
    {
        return 'loginuser';
    }
}
?>