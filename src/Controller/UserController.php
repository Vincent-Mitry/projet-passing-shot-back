<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateurs")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/membres", name="app_user_member", methods={"GET"})
     */
    public function listMember(UserRepository $userRepository): Response
    {
        return $this->render('user/member/index.html.twig', [
            'users' => $userRepository->getUsersMemberList(),
        ]);
    }

    /**
     * @Route("/membres/ajout", name="app_user_member_new", methods={"GET", "POST"})
     */
    public function newMember(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On doit hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            // On l'écrase dans le $user
            $user->setPassword($hashedPassword);
            
            $userRepository->add($user, true);

            $this->addFlash('success', 'Utilisateur ajouté');

            return $this->redirectToRoute('app_user_member', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/member/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/membres/{id}", name="app_user_member_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function showMember(User $user): Response
    {
        return $this->render('user/member/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/membres/{id}/modification", name="app_user_member_edit", methods={"GET", "POST"})
     */
    public function editMember(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Si mot de passe présent dans le formulaire,
            // on hâche
            $passwordInForm = $form->get('password')->getData();
            if ($passwordInForm) {
                // On doit hacher le mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $passwordInForm);
                // On l'écrase dans le $user
                $user->setPassword($hashedPassword);
            }

            $userRepository->add($user, true);

            $this->addFlash('success', 'Utilisateur modifié');

            return $this->redirectToRoute('app_user_member', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/member/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/membres/{id}", name="app_user_member_delete", methods={"POST"})
     */
    public function deleteMember(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
            $this->addFlash('success', 'Utilisateur supprimé.');
        }

        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/staff", name="app_user_staff", methods={"GET"})
     */
    public function listStaff(UserRepository $userRepository): Response
    {
        return $this->render('user/staff/index.html.twig', [
            'users' => $userRepository->getUsersMemberStaff(),
        ]);
    }

    /**
     * @Route("/staff/ajout", name="app_user_staff_new", methods={"GET", "POST"})
     */
    public function newStaff(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On doit hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            // On l'écrase dans le $user
            $user->setPassword($hashedPassword);
            
            $userRepository->add($user, true);

            $this->addFlash('success', 'Utilisateur ajouté');

            return $this->redirectToRoute('app_user_staff', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/staff/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/staff/{id}", name="app_user_staff_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function showStaff(User $user): Response
    {
        return $this->render('user/staff/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/staff/{id}/modification", name="app_user_staff_edit", methods={"GET", "POST"})
     */
    public function editStaff(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Si mot de passe présent dans le formulaire,
            // on hâche
            $passwordInForm = $form->get('password')->getData();
            if ($passwordInForm) {
                // On doit hacher le mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $passwordInForm);
                // On l'écrase dans le $user
                $user->setPassword($hashedPassword);
            }

            $userRepository->add($user, true);

            $this->addFlash('success', 'Utilisateur modifié');

            return $this->redirectToRoute('app_user_staff', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/staff/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/staff/{id}", name="app_user_staff_delete", methods={"POST"})
     */
    public function deleteStaff(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
            $this->addFlash('success', 'Utilisateur supprimé.');
        }

        return $this->redirectToRoute('app_user_staff', [], Response::HTTP_SEE_OTHER);
    }
}
