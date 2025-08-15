<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use function PHPUnit\Framework\throwException;

#[Route('/profile')]
final class ProfileController extends AbstractController
{
    #[Route('/user/{user_id}', name: 'app_profile_user', methods: ['GET'])]
    public function profile(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($user->getUserProfile()) {
            return $this->redirectToRoute('app_profile_show', ['id' => $user->getUserProfile()->getId()]);
        }

        return $this->redirectToRoute('app_profile_new');
    }

    #[Route(name: 'app_profile_index', methods: ['GET'])]
    public function index(UserProfileRepository $userProfileRepository): Response
    {
        return $this->render('profile/index.html.twig', [
            'user_profiles' => $userProfileRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_profile_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!($user instanceof User)) {
            throwException(new UnsupportedUserException('User should be of class '.User::class.' .Instead of '.$user::class));
        }

        $userProfile = $user->getUserProfile() ?: new UserProfile();
        $userProfile->setAccount($user);
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userProfile);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_show', ['id' => $userProfile->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/new.html.twig', [
            'user_profile' => $userProfile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_profile_show', methods: ['GET'])]
    public function show(UserProfile $userProfile): Response
    {
        return $this->render('profile/show.html.twig', [
            'user_profile' => $userProfile,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserProfile $userProfile, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'user_profile' => $userProfile,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(Request $request, UserProfile $userProfile, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userProfile->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($userProfile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
