<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get all users.
     *
     * @return User $user
     */
    public function getAll()
    {
        return $this->user
            ->get();
    }

    /**
     * Get the user by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->user
            ->where('id', $id)
            ->get();
    }

    /**
     * Save the User
     *
     * @param $data
     * @return User
     */
    public function save($data)
    {
        $user = new $this->user;

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        $user->save();

        return $user->fresh();
    }

    /**
     * Update the User
     *
     * @param $data
     * @return User
     */
    public function update($data, $id)
    {
        
        $user = $this->user->find($id);

        $user->name = $data['name'];
        $user->email = $data['email'];

        $user->update();

        return $user;
    }

    /**
     * Delete the User
     *
     * @param $data
     * @return User
     */
    public function delete($id)
    {
        
        $user = $this->user->find($id);
        $user->delete();

        return $user;
    }

    /**
     * Get all users.
     *
     * @return User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * find the user by id
     *
     * @param $id
     * @return mixed
     */
    public function findUser($id)
    {
        return $user = $this->user->find($id);
    }

}