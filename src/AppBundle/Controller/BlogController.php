<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use AppBundle\Entity\Post;
use AppBundle\Entity\Images;
use AppBundle\Entity\File;


use AppBundle\Ext\loggerClass;


class BlogController extends Controller
{
    /**
    * @Route("/post/", name="post")
    */
    public function indexAction(Request $request)
    {
		
		
		$session = new Session();
		// $session->start();
		
		// set and get session attributes
		$session->set('name', 'Drak - name set from index and Update');
		// $session->get('name');
		
		
		
		$post_array=$this->getDoctrine()
					->getRepository(Post::class)
					->findAll();
		
		$image_array=$this->getDoctrine()
					->getRepository(Images::class)
					->findAll();
		
		// echo $post_array;
		// exit('SUBMIT');
		
        return $this->render(
			'pages/index.html.twig', 
			[
				'post_array'=>$post_array,
				'image_array'=>$image_array,
			]
		); 
    }//END
	
	/**
    * @Route("/session_page/", name="session_page")
    */
    public function sessionTestAction(Request $request)
    {
		$session = new Session();
		// $session->start();
		
		// set and get session attributes
		$session->set('name', 'Drak - name set from index');
		// $session->get('name');
		
		$session_var=$session->get('name');
		
		
		
        return $this->render(
			'pages/session_page.html.twig', 
			[
				'session_var'=>$session_var,
			]
		); 
    }//END
	
	
	/**
    * @Route("/filesystem_page/", name="filesystem_page")
    */
    public function fileSystemTestAction(Request $request)
    {
		
		$file = new File();
		$form = $this->createFormBuilder($file)
				->add('file', TextType::class)
				->add('save', SubmitType::class)
				->getForm();
		
		$form->handleRequest($request);// сделать request на создание формы
		
		if( $form->isSubmitted() && $form->isValid() ){
			$fileSystem = new Filesystem();
			$folder_name = $form['file']->getData();
			// $fileSystem->mkdir('/tmp/photos', 0700);
			$fileSystem->mkdir($folder_name, 0700);
			
			return $this->redirectToRoute('post');
		}
		
        return $this->render(
			'pages/filesystem_page.html.twig', 
			[
				'form'=>$form->createView(),
			]
		); 
    }//END
	
	
	/**
    * @Route("/post/create/", name="create_post")
    */
	public function createAction(Request $request){
		
		$post = new Post();
		$form = $this->createFormBuilder($post)
				->add('title', TextType::class)
				->add('data', TextareaType::class)
				->add('author', TextType::class)
				->add('save', SubmitType::class)
				->getForm();
		// exit;
		$form->handleRequest($request);// сделать request на создание формы
		
		if( $form->isSubmitted() && $form->isValid() ){
			// exit('SUBMIT');
			// вызовим менеджер doctrine который создаст запись а базе данных
			
			{// сохранение с обработкой данных
				// достанем данные из формы
				// $title = $form['title']->getData();
				// $data = $form['data']->getData();
				// $author = $form['author']->getData();
				// loggerClass::writeLog($title, 'title');
				// loggerClass::writeLog($data, 'data');
				// loggerClass::writeLog($author, 'author');
				
				// $post->setTitle($title.'_mody_');
				// $post->setData($data.'_mody_');
				// $post->setAuthor($author.'_mody_');
				
				// $em = $this->getDoctrine()->getManager();
				// $em->persist($post);
				// $em->flush();
			}
			
			
			
			
			
		
			
			{// короткое сохранение данных
				// вызов менеджера который будет сохранять данные
				$em = $this->getDoctrine()->getManager();
				$em->persist($post);
				$em->flush();
			}
			
			return $this->redirectToRoute('post');
		}
		
		return $this->render(
			'pages/post_add.html.twig', 
			array('form'=>$form->createView())
		);
		
	}//END
	
	/**
    * @Route("/post/show/{id}/", name="show_post")
    */
	public function showAction(Request $request, $id){
		$post_item = $this->getDoctrine()
				->getRepository(Post::class)
				->find($id);
				
		return $this->render(
			'pages/show.html.twig', 
			array('post_item'=>$post_item)
		); 
	}//END
	
	/**
    * @Route("/post/edit/{id}/", name="edit_post")
    */
	public function editAction(Request $request, $id){
		
		$post = $this->getDoctrine()
				->getRepository(Post::class)
				->find($id);
		
		
		$form = $this->createFormBuilder($post)
				->add('title', TextType::class)
				->add('data', TextareaType::class)
				->add('author', TextType::class)
				->add('save', SubmitType::class)
				->getForm();
		
		// делаем запрос на форму
		$form->handleRequest($request);
		
		$post->setTitle($post->getTitle());
		$post->setData($post->getData());
		$post->setAuthor($post->getAuthor());
		
		if( $form->isSubmitted() && $form->isValid() ){
			
			{// сохранить данные без изменения
				$em = $this->getDoctrine()->getManager();
				$em->flush();
			}
			
			{// сохранить данные с изменением
				// достанем данные из формы
				// $title = $form['title']->getData();
				// $data = $form['data']->getData();
				// $author = $form['author']->getData();
				
				// $em = $this->getDoctrine()->getManager();
				// $repository = $this->getDoctrine()->getRepository(Post::class)->find($id);
				
				// $repository->setTitle($title.'__END');
				// $repository->setData($data.'__END');
				// $repository->setAuthor($author.'__END');
				// $em->flush();
			}
			
			return $this->redirectToRoute('post');
		}
		
		return $this->render(
			'pages/post_edit.html.twig', 
			array('form'=>$form->createView())
		);
		
	}//END
	
	/**
    * @Route("/post/delete/{id}/", name="delete_post")
    */
	public function deleteAction($id){
		$post = $this->getDoctrine()
				->getRepository(Post::class)
				->find($id);
		
		$em = $this->getDoctrine()->getManager();
		$em->remove($post);
		$em->flush();
		
		return $this->redirectToRoute('post');
	}//END
	
}//END class

?>