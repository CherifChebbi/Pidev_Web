<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    public function likeRestaurant(Request $request, Restaurant $restaurant): JsonResponse
    {
        $user = $this->getUser();
    
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        
        // Check if the user has already liked/disliked the restaurant
        $existingLike = $entityManager->getRepository(Like::class)->findOneBy(['user' => $user, 'restaurant' => $restaurant]);
    
        if ($existingLike) {
            // User already liked/disliked, handle accordingly (e.g., remove the like)
            $entityManager->remove($existingLike);
        } else {
            // User has not liked/disliked, create a new like
            $like = new Like();
            $like->setUser($user);
            $like->setRestaurant($restaurant);
            $like->setLiked(true); // Set to false for dislike
            $entityManager->persist($like);
        }
    
        $entityManager->flush();
    
        // Return any relevant data (e.g., like count)
        return new JsonResponse(['likes' => $restaurant->getLikes()->count()]);
    }
}
