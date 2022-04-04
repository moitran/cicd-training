<?php

namespace App\Controller;

use App\Entity\Superheroes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SuperheroBySlug extends AbstractController
{
    /**
     * @param string $slug
     *
     * @return object[]
     */
    public function __invoke(string $slug)
    {
        $superhero = $this->getDoctrine()
            ->getRepository(Superheroes::class)
            ->findBy(
                ['slug' => $slug]
            );

        if (!$superhero) {
            throw $this->createNotFoundException(
                'No superhero found for this slug'
            );
        }

        return $superhero;
    }
}
