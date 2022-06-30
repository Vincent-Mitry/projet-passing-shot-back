<?php

namespace App\Controller;

use App\Entity\Gender;
use App\Form\GenderType;
use App\Repository\GenderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/genres")
 */
class GenderController extends AbstractController
{
    /**
     * @Route("", name="app_gender", methods={"GET"})
     */
    public function index(GenderRepository $genderRepository): Response
    {
        return $this->render('gender/index.html.twig', [
            'genders' => $genderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="app_gender_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GenderRepository $genderRepository): Response
    {
        $gender = new Gender();
        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genderRepository->add($gender, true);

            $this->addFlash('success', $gender->getType() . ' ajouté !');

            return $this->redirectToRoute('app_gender', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gender/new.html.twig', [
            'gender' => $gender,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_gender_show", methods={"GET"})
     */
    public function show(Gender $gender = null): Response
    {
        if ($gender === null) {
            throw $this->createNotFoundException('genre non trouvé');
        }
        return $this->render('gender/show.html.twig', [
            'gender' => $gender,
        ]);
    }

    /**
     * @Route("/{id}/modification", name="app_gender_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Gender $gender = null, GenderRepository $genderRepository): Response
    {
        if ($gender === null) {
            throw $this->createNotFoundException('genre non trouvé');
        }
        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genderRepository->add($gender, true);

            $this->addFlash('success', $gender->getType() . ' modifié !');

            return $this->redirectToRoute('app_gender', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gender/edit.html.twig', [
            'gender' => $gender,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_gender_delete", methods={"POST"})
     */
    public function delete(Request $request, Gender $gender = null, GenderRepository $genderRepository): Response
    {
        if ($gender === null) {
            throw $this->createNotFoundException('genre non trouvé');
        }
        if ($this->isCsrfTokenValid('delete' . $gender->getId(), $request->request->get('_token'))) {
            $genderRepository->remove($gender, true);

            $this->addFlash('warning', $gender->getType() . ' supprimé !');
        }

        return $this->redirectToRoute('app_gender', [], Response::HTTP_SEE_OTHER);
    }
}
