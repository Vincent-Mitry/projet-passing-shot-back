<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clubs")
 */
class ClubController extends AbstractController
{
    /**
     * @Route("", name="app_club", methods={"GET"})
     */
    public function list(ClubRepository $clubRepository): Response
    {
        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajout", name="app_club_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClubRepository $clubRepository, UserRepository $userRepository): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $clubRepository->add($club, true);

            $this->addFlash('success', $club->getName() . ' ajouté !');

            return $this->redirectToRoute('app_club', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club/new.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_club_show", methods={"GET"})
     */
    public function show(Club $club): Response
    {
        if ($club === null) {
            throw $this->createNotFoundException('Club non trouvé');
        }

        return $this->render('club/show.html.twig', [
            'club' => $club,
        ]);
    }

    /**
     * @Route("/{id}/modification", name="app_club_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        if ($club === null) {
            throw $this->createNotFoundException('Club non trouvé');
        }

        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clubRepository->add($club, true);

            $this->addFlash('success', $club->getName() . ' modifié !');

            return $this->redirectToRoute('app_club', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club/edit.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_club_delete", methods={"POST"})
     */
    public function delete(Request $request, Club $club, ClubRepository $clubRepository): Response
    {
        if ($club === null) {
            throw $this->createNotFoundException('Club non trouvé');
        }
        if ($this->isCsrfTokenValid('delete' . $club->getId(), $request->request->get('_token'))) {
            $clubRepository->remove($club, true);

            $this->addFlash('warning', $club->getName() . ' supprimé!');
        }

        return $this->redirectToRoute('app_club', [], Response::HTTP_SEE_OTHER);
    }
}
