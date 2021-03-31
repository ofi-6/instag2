<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CreatePostType;
use App\Form\UpdatePostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    // /**
    //  * @Route("/pop", name="pop")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('post/index.html.twig', [
    //         'controller_name' => 'PostController',
    //     ]);
    // }

    /**
     * @Route("/", name="app_home")
     */
    public function home(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig', ['posts' => $posts]);
        // alternative : seulement si egalite de nommage entre (ici) posts et => posts
        // cad array mem nom que la variable qui le compose
       //// return $this->render('post/index.html.twig', compact('posts'));
    }

   
    /**
    * @Route("/post/new", name="app_post_new", methods="GET|POST")
    */
    public function createPost(EntityManagerInterface $em, Security $security, Request $request)
    {
            $post = new Post;
            $form = $this->createForm(CreatePostType::class, $post);
    
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $security->getUser();
                $post->setAuthor($user);
    
                $em->persist($post);
                $em->flush();
    
                $this->addFlash('success', 'Your POST has been created successfully.');
    
                return $this->redirectToRoute('app_home');
            }
  
            return $this->render('post/create.html.twig', [
                'form' => $form->createView(),
            ]);
    }


 /**
     * 
     * @Route("/post/edit/{id<\d+>}", name="app_post_edit",methods="GET|POST|PUT")
     * 
     */
    public function edit( Request $request,EntityManagerInterface $em, Security $security,Post $post)
    {

        // $post = new Post();
        $form= $this->createForm(UpdatePostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user= $security->getUser();
            if ($user === $post->getAuthor()) 
            {
                //  $em->persist($post);
                // pas de $ post dans flush()
            //   dd($post);
              $em->flush();
              $this->addFlash('success', 'Post  updated! Knowledge is power!');
            }
            else {
                $this->addFlash('danger', 'Post NOT updated! Knowledge is power!');
            }
            return $this->redirectToRoute('app_home');
        }
        return $this->render('post/edit.html.twig',
        ['form'=> $form->createView(),
        'post'=> $post,
        ]);
    }

    /**
     * 
     * @Route("/post/delete/{id<\d+>}", name="app_post_delete",methods="DELETE")
     * 
     */

public function delete (Request $request, Security $security , 
Post $post , EntityManagerInterface $em)
{
$user=$security->getUser();

if ($this->isCsrfTokenValid('post_delete_'.$post->getId(),$request->request->get('csrf_token')))

{
if ($user===$post->getAuthor())
   {
    $em->remove($post);
    $em->flush();
    $this->addFlash('success', 'supression ok');
    }
    else {$this->addFlash('danger', 'KO supression KO pas auteur');}
}
else {$this->addFlash('danger', 'KO supression KO pas valide');}
return $this->redirectToRoute('app_home');
}


}






