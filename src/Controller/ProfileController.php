<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Repository\UserProfileRepository;
use App\Security\Voter\UserProfileVoter;
use App\Trait\ProfiledUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ProfileController extends AbstractController
{
    use ProfiledUserTrait;

    #[Route('/user', name: 'app_profile_user', methods: ['GET'])]
    public function profile(): Response
    {
        if ($this->getUserProfile()) {
            return $this->redirectToRoute('app_profile_show');
        }

        return $this->redirectToRoute('app_profile_new');
    }

    #[Route(name: 'app_profile_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserProfileRepository $userProfileRepository): Response
    {
        return $this->render('profile/index.html.twig', [
            'user_profiles' => $userProfileRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_profile_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUserProfile()) {
            return $this->redirectToRoute('app_profile_edit');
        }

        $userProfile = new UserProfile();
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
        if (!$userProfile) {
            return $this->redirectToRoute('app_profile_new');
        }

        return $this->render('profile/show.html.twig', [
            'user_profile' => $userProfile,
        ]);
    }

    #[Route('/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userProfile = $this->getUserProfile();
        if (!$userProfile) {
            return $this->redirectToRoute('app_profile_new');
        }
        $this->denyAccessUnlessGranted(UserProfileVoter::EDIT, $userProfile);

        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'user_profile' => $userProfile,
            'form' => $form,
        ]);
    }

    #[Route('/delete', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userProfile = $this->getUserProfile();
        if (!$userProfile) {
            throw $this->createNotFoundException();
        }
        $this->denyAccessUnlessGranted(UserProfileVoter::DELETE, $userProfile);

        if ($this->isCsrfTokenValid('delete'.$userProfile->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($userProfile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}
