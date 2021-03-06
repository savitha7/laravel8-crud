<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Exception;

class UserController extends AppBaseController
{
    /**
     * @var userService
     */
    protected $userService;

    /**
     * UserController Constructor
     *
     * @param UserService $userService
     *
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = ['status' => true];

        try {
            $result['data'] = $this->userService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        $result['page_set'] = 'users';

        return view('pages.users.users', $result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $result['page_set'] = 'user_create';

        return view('pages.users.create', $result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
            'password_confirmation'
        ]);

        $result = ['status' => true];

        try {
            $user = $this->userService->saveUserData($data);
            $result['message'] = trans('notification.created_s', ['obj_name'=>'user']);
            $result['url'] = route('users');
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        return $this->sendResponse($result, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result['page_set'] = 'user_edit';

        $user = $this->userService->findUser($id);
        $result['name'] = $user->name;
        $result['email'] = $user->email;

        return view('pages.users.edit', $result);
    }

    /**
     * Update the user.
     *
     * @param Request $request
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'name',
            'email'
        ]);

        $result = ['status' => true];

        try {
            $user = $this->userService->updateUser($data, $id);
            $result['message'] = trans('notification.updated_s', ['obj_name'=>'user']);
            $result['url'] = route('users');
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        return $this->sendResponse($result, $request);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $result = ['status' => true];

        try {
            $user = $this->userService->deleteById($id);
            $result['message'] = trans('notification.deleted_s', ['obj_name'=>'user']);
        } catch (Exception $e) {
            $result = [
                'status' => false,
                'errors' => $e->getMessage()
            ];
        }

        return $this->sendResponse($result, $request);
    }

    public function getUsers(Request $request){

        $result = $this->userService->getUsers($request);

        return $this->sendResponse($result, $request);
    }

}
