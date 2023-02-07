<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\DTO\EditCategoryModel;
use App\Form\Admin\EditCategoryFormType;
use App\Form\Handler\CategoryFormHandler;
use App\Repository\CategoryRepository;
use App\Utils\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findBy(['isDeleted' => false],['id' => 'DESC']);

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, CategoryFormHandler $categoryFormHandler ,int $id = null): Response
    {
        
   
        $entityManager = $this->getDoctrine()->getManager();
        if ($id) {
            $category = $entityManager->getRepository(Category::class)->find($id);
        } else {
            $category = new Category();
        }


        $editCategoryModel = EditCategoryModel::makeFromCategory($category);

        $form = $this->createForm(EditCategoryFormType::class, $editCategoryModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $categoryFormHandler->processEditForm($editCategoryModel);
            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_category_edit', ['id' => $category->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form!.');
        }

        return $this->render('admin/category/edit.html.twig',[
            'category' => $category,
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(CategoryManager $categoryManager, $id = 0 )  //: Response ,$id = 0
    {

        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        $categoryManager->remove($category);

        $this->addFlash('warning', 'The category was successfully deleted!.');
        
        return $this->redirectToRoute('admin_category_list');
    }


}
