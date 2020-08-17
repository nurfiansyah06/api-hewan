<?php

namespace App\Policies;

use App\User;
use App\Animal;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnimalPolicy
{
    use HandlesAuthorization;

   public function update(User $user, Animal $animal)
   {
       return $user->ownsAnimal($animal);
   }
}
