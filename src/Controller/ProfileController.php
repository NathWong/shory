<?php

namespace App\Controller;

use App\Contract\ProfiledUserInterface;
use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ProfileController extends AbstractController
{
    #[Route('/user', name: 'app_profile_user', methods: ['GET'])]
    public function profile(): Response
    {
        if ($this->getUserProfile()) {
            return $this->redirectToRoute('app_profile_show');
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
        $userProfile = $this->getUserProfile() ?: new UserProfile();
        $userProfile->setAccount($this->getProfiledUser());
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userProfile);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_show');
        }

        return $this->render('profile/new.html.twig', [
            'user_profile' => $userProfile,
            'form' => $form,
        ]);
    }

    #[Route('/show', name: 'app_profile_show', methods: ['GET'])]
    public function show(): Response
    {
        $userProfile = $this->getUserProfile();

        return $this->render('profile/show.html.twig', [
            'user_profile' => $userProfile,
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
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

    private function getProfiledUser(): ProfiledUserInterface
    {
        $user = $this->getUser();

        if (!($user instanceof ProfiledUserInterface)) {
            throw new UnsupportedUserException('User should implement '.ProfiledUserInterface::class);
        }

        return $user;
    }

    private function getUserProfile(): ?UserProfile
    {
        return $this->getProfiledUser()->getUserProfile();
    }
}
