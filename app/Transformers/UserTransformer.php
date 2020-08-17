<?php
namespace App\Transformers;
use App\User;
use League\Fractal\TransformerAbstract;
use App\Transformers\AnimalTransformer;
class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
            'animals'
        ];

    public function transform(User $user)
    {
        return [
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'password'      => $user->password,
            'no_hp'         => $user->no_hp,
            'tempat_lahir'  => $user->tmpt_lahir,
            'tanggal_lahir' => $user->tanggal_lahir,
            'photo'         => '/storage/'.$user->photo,
            'bio'           => $user->bio, 
            'roles'         => $user->roles, 
            'registered'    => $user->created_at->diffForHumans()
        ];
    }

    public function includeAnimals(User $user)
    {
        $animals = $user->animals;

        return $this->collection($animals, new AnimalTransformer);
    }
}



?>