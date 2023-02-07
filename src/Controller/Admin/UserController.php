<?php

namespace App\Controller\Admin;

use App\Entity\StaticStorage\UserStaticStorage;
use App\Entity\User;
use App\Form\EditUserFormType;
use App\Form\Handler\UserFormHandler;
use App\Repository\UserRepository;
use App\Utils\Manager\CategoryManager;
use App\Utils\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/user", name="admin_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(UserRepository $userRepository): Response
    {

        if(!$this->isGranted(UserStaticStorage::USER_ROLE_SUPER_ADMIN)) {
            return $this->redirectToRoute('admin_dashboard_show');
        }


        $users = $userRepository->findBy(['isDeleted' => false],['id' => 'DESC']);

        return $this->render('admin/user/list.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, UserFormHandler $userFormHandler, int $id = null): Response  // , CategoryFormHandler $categoryFormHandler 
    {
        
        if(!$this->isGranted(UserStaticStorage::USER_ROLE_SUPER_ADMIN)) {
            return $this->redirectToRoute('admin_dashboard_show');
        }

        $entityManager = $this->getDoctrine()->getManager();
        if ($id) {
            $user = $entityManager->getRepository(User::class)->find($id);
        } else {
            $user = new User();
        }

        $form = $this->createForm(EditUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userFormHandler->processEditForm($form);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form!');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(UserManager $userManager, $id = 0 )  //: Response ,$id = 0
    {

    
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        $userManager->remove($user);

        $this->addFlash('warning', 'The category was successfully deleted!.');
      

        return $this->redirectToRoute('admin_user_list');
    }


}
