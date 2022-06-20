<?php

namespace App\Controller;

use App\Entity\Court;
use App\Form\CourtType;
use App\Entity\BlockedCourt;
use App\Form\BlockedCourtType;
use App\Repository\ClubRepository;
use App\Repository\CourtRepository;
use App\Repository\BlockedCourtRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    public function new(Request $request, CourtRepository $courtRepository, ClubRepository $clubRepository): Response
    {
        $court = new Court();
        $form = $this->createForm(CourtType::class, $court);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // default id for the club is 1
            // $club = $clubRepository->findOneById(1);
            // $court->setClub($club);

            $courtRepository->add($court, true);

            $this->addFlash('success', 'Terrain ajouté');

            return $this->redirectToRoute('app_court', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('court/new.html.twig', [
            'court' => $court,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_court_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Court $court): Response
    {
        return $this->render('court/show.html.twig', [
            'court' => $court,
        ]);
    }

    /**
     * @Route("/{id}/modification", name="app_court_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Court $court, CourtRepository $courtRepository, ClubRepository $clubRepository): Response
    {
        $form = $this->createForm(CourtType::class, $court);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $club = $clubRepository->findOneById(1);
            $court->setClub($club);

            $courtRepository->add($court, true);

            $this->addFlash('success', 'Terrain modifé');

            return $this->redirectToRoute('app_court', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('court/edit.html.twig', [
            'court' => $court,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_court_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Court $court, CourtRepository $courtRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$court->getId(), $request->request->get('_token'))) {
            $courtRepository->remove($court, true);
        }

        $this->addFlash('success', 'Terrain supprimé');

        return $this->redirectToRoute('app_court', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/liste-terrains-bloqués", name="app_blocked_court_list", methods={"GET"})
     */
    public function listBlockedCourts(BlockedCourtRepository $blockedCourtRepository): Response
    {
        return $this->render('court/blocked/index.html.twig', [
            'blockedCourts' => $blockedCourtRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{court_id}/bloquer", name="app_court_block", methods={"GET", "POST"}, requirements={"court_id"="\d+"})
     * @ParamConverter("court", options={"id" = "court_id"})
     */
    public function block(Request $request, Court $court = null, BlockedCourtRepository $blockedCourtRepository): Response
    {
        if ($court === null) {
            throw $this->createNotFoundException('Terrain non trouvé');
        }
        
        $blockedCourt = new BlockedCourt();

        $blockedCourt->setUser($this->getUser())
                    ->setCourt($court);

        $form = $this->createForm(BlockedCourtType::class, $blockedCourt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $blockedCourtRepository->add($blockedCourt, true);

            $this->addFlash('success', 'Terrain bloqué');

            return $this->redirectToRoute('app_blocked_court_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('court/blocked/new.html.twig', [
            'form' => $form,
            'court' => $court,
        ]);
    }
}
