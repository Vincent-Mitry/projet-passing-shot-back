<?php

namespace App\Controller;

use App\Entity\Surface;
use App\Form\SurfaceType;
use App\Repository\SurfaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/surface")
 */
class SurfaceController extends AbstractController
{
    /**
     * @Route("/", name="app_surface", methods={"GET"})
     */
    public function index(SurfaceRepository $surfaceRepository): Response
    {
        return $this->render('surface/index.html.twig', [
            'surfaces' => $surfaceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="app_surface_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SurfaceRepository $surfaceRepository): Response
    {
        $surface = new Surface();
        $form = $this->createForm(SurfaceType::class, $surface);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $surfaceRepository->add($surface, true);

            return $this->redirectToRoute('app_surface', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('surface/new.html.twig', [
            'surface' => $surface,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_surface_show", methods={"GET"})
     */
    public function show(Surface $surface): Response
    {
        if ($surface === null) {
            throw $this->createNotFoundException('Pas de surface trouvée');
        }
        return $this->render('surface/show.html.twig', [
            'surface' => $surface,
        ]);
    }

    /**
     * @Route("/{id}/modification", name="app_surface_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Surface $surface, SurfaceRepository $surfaceRepository): Response
    {
        if ($surface === null) {
            throw $this->createNotFoundException('Pas de surface trouvée');
        }

        $form = $this->createForm(SurfaceType::class, $surface);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $surfaceRepository->add($surface, true);

            return $this->redirectToRoute('app_surface', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('surface/edit.html.twig', [
            'surface' => $surface,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_surface_delete", methods={"POST"})
     */
    public function delete(Request $request, Surface $surface, SurfaceRepository $surfaceRepository): Response
    {
        if ($surface === null) {
            throw $this->createNotFoundException('Pas de surface trouvée');
        }

        if ($this->isCsrfTokenValid('delete' . $surface->getId(), $request->request->get('_token'))) {
            $surfaceRepository->remove($surface, true);
        }

        return $this->redirectToRoute('app_surface', [], Response::HTTP_SEE_OTHER);
    }
}
