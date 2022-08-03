<?php

namespace App\Controller;

use App\Entity\Surface;
use App\Form\SurfaceType;
use App\Repository\SurfaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/surface")
 */
class SurfaceController extends AbstractController
{
    /**
     * @Route("", name="app_surface", methods={"GET"})
     */
    public function index(SurfaceRepository $surfaceRepository): Response
    {
        return $this->render('surface/index.html.twig', [
            'surfaces' => $surfaceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="app_surface_new", methods={"GET", "POST"}, requirements={"id"="\d+"})
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
     * @Route("/{id}", name="app_surface_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Surface $surface = null): Response
    {
        if ($surface === null) {
            throw $this->createNotFoundException('la surface demandée est introuvable');
        }
        return $this->render('surface/show.html.twig', [
            'surface' => $surface,
        ]);
    }

    /**
     * @Route("/{id}/modification", name="app_surface_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Surface $surface = null, SurfaceRepository $surfaceRepository): Response
    {
        if ($surface === null) {
            throw $this->createNotFoundException('la surface demandée est introuvable');
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
    public function delete(Request $request, Surface $surface = null, SurfaceRepository $surfaceRepository): Response
    {
        if ($surface === null) {
            throw $this->createNotFoundException('la surface demandée est introuvable');
        }

        if ($this->isCsrfTokenValid('delete' . $surface->getId(), $request->request->get('_token'))) {
            $surfaceRepository->remove($surface, true);
        }

        return $this->redirectToRoute('app_surface', [], Response::HTTP_SEE_OTHER);
    }
}
