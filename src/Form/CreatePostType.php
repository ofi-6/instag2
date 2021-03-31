<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
        ->add('title')
        ->add('text')
        ->add('imageFile',VichImageType::class,[
            'required' => true,
            'allow_delete' => true,
            'delete_label' => 'Remove image',
            'download_label' => 'Download image',
            'download_uri' => true,
            'image_uri' => true,
            'asset_helper' => true,
        ])
                    // ci dessous a été créé par défaut
         // ->add('createdAt')  
         // ->add('updateAt')
         // ->add('author')
    ;
    
    
    
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
