# PolidogSimpleApiBundle

## Installation

```
$ composer require polidog/simple-api-bundle "dev-master"
```

Add config/bundles.php

```php
<?php

return [
...



    Polidog\SimpleApiBundle\PolidogSimpleApiBundle::class => ['all' => true]
];

```

## Introduce bundle configuration to your config file

```yaml
# config/packages/polidog_simple_api.yml

polidog_simple_api: ~
```

## Usage

```php
<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\LoginUser;
use Polidog\SimpleApiBundle\Annotations\Api;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    private $userRepository;

    /**
     * for php7
     * @Route("/user/{id}")
     * @Api()
     */
    #[Route('/user/{$id}', name: 'app_user')]
    #[Api]
    public function me($id): array
    {
        $user = $this->userRepository->find($id);
        return [
            'id' => $user->getId(),
            'name' => $user->getUsername(),
            'avatar' => $user->getAvatar(),
        ];
    }

    /**
     * for php7
     * @Route("/user/post", methods={"POST"})
     * @Api(statusCode=201)
     */
    #[Route('/user/post', name: 'POST')]
    #[Api(201)]
    public function post(Request $request): array
    {
        // TODO save logic.
        return [
            'status' => 'ok',
        ];
    }
}

```

