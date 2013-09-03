<?php
namespace MyBlog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MyBlog\Entity;

class BlogController extends AbstractActionController
{

    public function indexAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        if ($this->isAllowed('controller/MyBlog\Controller\BlogPost:edit')) {
            $posts = $objectManager
                ->getRepository('\MyBlog\Entity\BlogPost')
                ->findBy(array(), array('created' => 'DESC'));
        }
        else {
            $posts = $objectManager
                ->getRepository('\MyBlog\Entity\BlogPost')
                ->findBy(array('state' => 1), array('created' => 'DESC'));
        }

        $posts_array = array();
        foreach ($posts as $post) {
            $posts_array[] = $post->getArrayCopy();
        }

        $view = new ViewModel(array(
            'posts' => $posts_array,
        ));

        return $view;
    }

    public function viewAction()
    {
        // Check if id and blogpost exists.
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Blogpost id doesn\'t set');
            return $this->redirect()->toRoute('blog');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $post = $objectManager
            ->getRepository('\MyBlog\Entity\BlogPost')
            ->findOneBy(array('id' => $id));

        if (!$post) {
            $this->flashMessenger()->addErrorMessage(sprintf('Blogpost with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('blog');
        }

        // Render template.
        $view = new ViewModel(array(
            'post' => $post->getArrayCopy(),
        ));

        return $view;
    }

    public function addAction()
    {
        $form = new \MyBlog\Form\BlogPostForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $blogpost = new \MyBlog\Entity\BlogPost();

                $blogpost->exchangeArray($form->getData());

                $blogpost->setCreated(time());
                $blogpost->setUserId(0);

                $objectManager->persist($blogpost);
                $objectManager->flush();

                $message = 'Blogpost succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('blog');
            }
            else {
                $message = 'Error while saving blogpost';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        // Check if id set.
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Blogpost id doesn\'t set');
            return $this->redirect()->toRoute('blog');
        }

        // Create form.
        $form = new \MyBlog\Form\BlogPostForm();
        $form->get('submit')->setValue('Save');

        $request = $this->getRequest();
        if (!$request->isPost()) {

            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $post = $objectManager
                ->getRepository('\MyBlog\Entity\BlogPost')
                ->findOneBy(array('id' => $id));

            if (!$post) {
                $this->flashMessenger()->addErrorMessage(sprintf('Blogpost with id %s doesn\'t exists', $id));
                return $this->redirect()->toRoute('blog');
            }

            // Fill form data.
            $form->bind($post);
            return array('form' => $form, 'id' => $id, 'post' => $post);
        }
        else {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $data = $form->getData();
                $id = $data['id'];
                try {
                    $blogpost = $objectManager->find('\MyBlog\Entity\BlogPost', $id);
                }
                catch (\Exception $ex) {
                    return $this->redirect()->toRoute('blog', array(
                        'action' => 'index'
                    ));
                }

                $blogpost->exchangeArray($form->getData());

                $objectManager->persist($blogpost);
                $objectManager->flush();

                $message = 'Blogpost succesfully saved!';
                $this->flashMessenger()->addMessage($message);

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('blog');
            }
            else {
                $message = 'Error while saving blogpost';
                $this->flashMessenger()->addErrorMessage($message);
                return array('form' => $form, 'id' => $id);
            }
        }
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Blogpost id doesn\'t set');
            return $this->redirect()->toRoute('blog');
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                try {
                    $blogpost = $objectManager->find('MyBlog\Entity\BlogPost', $id);
                    $objectManager->remove($blogpost);
                    $objectManager->flush();
                }
                catch (\Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Error while deleting data');
                    return $this->redirect()->toRoute('blog', array(
                        'action' => 'index'
                    ));
                }

                $this->flashMessenger()->addMessage(sprintf('Blogpost %d was succesfully deleted', $id));
            }

            return $this->redirect()->toRoute('blog');
        }

        return array(
            'id'    => $id,
            'post' => $objectManager->find('MyBlog\Entity\BlogPost', $id)->getArrayCopy(),
        );
    }
}
