<?php

namespace App\Controller;

use App\Entity\Court;
use App\Form\CourtType;
use App\Repository\CourtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/terrains")
 */
class CourtController extends AbstractController
{
    /**
     * @Route("", name="app_court", methods={"GET"})
     */
    public function list(CourtRepository $courtRepository): Response
    {
        return $this->render('court/index.html.twig', [
            'courts' => $courtRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="app_court_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CourtRepository $courtRepository): Response
    {
        $court = new Court();
        $form = $this->createForm(CourtType::class, $court);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courtRepository->add($court, true);

            return $this->redirectToRoute('app_court_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('court/new.html.twig', [
            'court' => $court,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_court_show", methods={"GET"})
     */
    public function show(Court $court): Response
    {
        return $this->render('court/show.html.twig', [
            'court' => $court,
        ]);
    }

    /**
     * @Route("/{id}/modification", name="app_court_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Court $court, CourtRepository $courtRepository): Response
    {
        $form = $this->createForm(CourtType::class, $court);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courtRepository->add($court, true);

            return $this->redirectToRoute('app_court_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('court/edit.html.twig', [
            'court' => $court,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_court_delete", methods={"POST"})
     */
    public function delete(Request $request, Court $court, CourtRepository $courtRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$court->getId(), $request->request->get('_token'))) {
            $courtRepository->remove($court, true);
        }

        return $this->redirectToRoute('app_court_index', [], Response::HTTP_SEE_OTHER);
    }
}
