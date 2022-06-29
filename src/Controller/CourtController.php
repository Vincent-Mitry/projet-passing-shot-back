<?php

namespace App\Controller;

use App\Entity\Court;
use App\Form\CourtType;
use App\Service\SendEmail;
use App\Entity\BlockedCourt;
use App\Form\BlockedCourtType;
use App\Repository\ClubRepository;
use App\Repository\CourtRepository;
use App\Service\BlockedCourtService;
use App\Repository\BlockedCourtRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
    public function list(CourtRepository $courtRepository, BlockedCourtService $blockedCourtService): Response
    {
        return $this->render('court/index.html.twig', [
            'courts' => $courtRepository->findAll(),
            'currentCourtsListBlocked' => $blockedCourtService->currentCourtsListBlocked()
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
    public function show(Court $court = null, BlockedCourtService $blockedCourtService): Response
    {
        if ($court === null) {
            throw $this->createNotFoundException('Terrain introuvable');
        }

        return $this->render('court/show.html.twig', [
            'court' => $court,
            'isCurrentlyBlocked' => $blockedCourtService->currentBlockedCourts()
        ]);
    }
    /**
     * @Route("/{id}/modification", name="app_court_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Court $court = null, CourtRepository $courtRepository, ClubRepository $clubRepository): Response
    {
        if ($court === null) {
            throw $this->createNotFoundException('Terrain introuvable');
        }

        $form = $this->createForm(CourtType::class, $court);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
    public function delete(Request $request, Court $court = null, CourtRepository $courtRepository): Response
    {
        if ($court === null) {
            throw $this->createNotFoundException('Terrain introuvable');
        }
        if ($this->isCsrfTokenValid('delete' . $court->getId(), $request->request->get('_token'))) {
            $courtRepository->remove($court, true);
        }

        $this->addFlash('success', 'Terrain supprimé');

        return $this->redirectToRoute('app_court', [], Response::HTTP_SEE_OTHER);
    }

    //----------------------------------//
    //--------- BLOCKED COURTS ---------//
    //----------------------------------//

    /**
     * @Route("/{court_id}/bloquer", name="app_court_block", methods={"GET", "POST"}, requirements={"court_id"="\d+"})
     * @ParamConverter("court", options={"id" = "court_id"})
     */
    public function block(
        Request $request,
        Court $court = null,
        BlockedCourtRepository $blockedCourtRepository,
        BlockedCourtService $blockedCourtService
    ): Response {
        if ($court === null) {
            throw $this->createNotFoundException('Terrain introuvable');
        }

        $blockedCourt = new BlockedCourt();

        $blockedCourt->setUser($this->getUser())
            ->setCourt($court);

        $form = $this->createForm(BlockedCourtType::class, $blockedCourt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blockedCourtService->handleExistingReservationsOnBlocked(
                $court,
                $blockedCourt,
                $blockedCourt->getStartDatetime(),
                $blockedCourt->getEndDatetime(),
            );

            $blockedCourtRepository->add($blockedCourt, true);

            $this->addFlash('success', 'Fermeture temporaire ajoutée (' . $court->getName() . ')');

            return $this->redirectToRoute('app_blocked_courts_by_court', ['court_id' => $court->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('court/blocked/new.html.twig', [
            'form' => $form,
            'court' => $court,
        ]);
    }

    /**
     * List of blocked courts for current court
     * 
     * @Route("/{court_id}/fermetures-temporaires", name="app_blocked_courts_by_court", methods={"GET"}, requirements={"court_id"="\d+", "id"="\d+"})
     * @ParamConverter("court", options={"id" = "court_id"})
     */
    public function listBlockedCourts(
        BlockedCourtRepository $blockedCourtRepository,
        Court $court = null,
        BlockedCourtService $blockedCourtService
    ): Response {
        if ($court === null) {
            throw $this->createNotFoundException('Terrain introuvable');
        }

        return $this->render('court/blocked/blocked_courts_by_court.html.twig', [
            'blockedCourts' => $blockedCourtRepository->findBlockedCourtsByCourt($court->getId()),
            'court' => $court,
            'currentBlockedCourts' => $blockedCourtService->currentBlockedCourts(),
            'futureBlockedCourts' => $blockedCourtService->futureBlockedCourts(),
            'pastBlockedCourts' => $blockedCourtService->pastBlockedCourts()
        ]);
    }

    /**
     * @Route("/{court_id}/fermetures-temporaires/{id}", name="app_blocked_court_show", methods={"GET"}, requirements={"court_id"="\d+", "id"="\d+"})
     * @ParamConverter("court", options={"id" = "court_id"})
     * @ParamConverter("blocked_court", options={"id" = "id"})
     */
    public function showBlocked(Court $court = null, BlockedCourt $blockedCourt = null): Response
    {
        if ($court === null) {
            throw $this->createNotFoundException('Terrain introuvable');
        }

        if ($blockedCourt === null) {
            throw $this->createNotFoundException('Fermeture temporaire non trouvée');
        }

        return $this->render('court/blocked/show.html.twig', [
            'court' => $court,
            'blockedCourt' => $blockedCourt,
        ]);
    }

    /**
     * @Route("/{court_id}/fermetures-temporaires/modification/{id}", name="app_blocked_court_edit", methods={"GET", "POST"}, requirements={"court_id"="\d+", "id"="\d+"})
     * @ParamConverter("court", options={"id" = "court_id"})
     * @ParamConverter("blocked_court", options={"id" = "id"})
     */
    public function editBlocked(
        Request $request,
        Court $court = null,
        BlockedCourt $blockedCourt = null,
        BlockedCourtRepository $blockedCourtRepository
    ): Response {
        if ($court === null) {
            throw $this->createNotFoundException('Terrain introuvable');
        }

        if ($blockedCourt === null) {
            throw $this->createNotFoundException('Fermeture temporaire non trouvée');
        }

        $form = $this->createForm(BlockedCourtType::class, $blockedCourt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $blockedCourtRepository->add($blockedCourt, true);

            $this->addFlash('success', 'Fermeture temporaire modifée (' . $court->getName() . ')');

            return $this->redirectToRoute('app_blocked_courts_by_court', ['court_id' => $court->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('court/blocked/edit.html.twig', [
            'court' => $court,
            'form' => $form,
            'blockedCourt' => $blockedCourt,
        ]);
    }

    /**
     * @Route("/fermeture-temporaire/{id}", name="app_blocked_court_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function deleteBlocked(Request $request, BlockedCourt $blockedCourt, BlockedCourtRepository $blockedCourtRepository): Response
    {
        
        if ($this->isCsrfTokenValid('delete' . $blockedCourt->getId(), $request->request->get('_token'))) {
            $blockedCourtRepository->remove($blockedCourt, true);
        }

        $this->addFlash('success', 'Fermeture temporaire supprimée (' . $blockedCourt->getCourt()->getName() . ')');

        return $this->redirectToRoute('app_court', [], Response::HTTP_SEE_OTHER);
    }
}
