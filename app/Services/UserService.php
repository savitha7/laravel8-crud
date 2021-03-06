<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Validation\Rule;

class UserService extends BaseService
{
    /**
     * @var $userRepository
     */
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }    

    /**
     * Get all user.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    /**
     * Get the user by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        $id = $this->decrypt($id);
        return $this->userRepository->getById($id);
    }

    /**
     * Get the user by id.
     *
     * @param $id
     * @return String
     */
    public function findUser($id)
    {
        $id = $this->decrypt($id);
        return $this->userRepository->findUser($id);
    }

    /**
     * Validate the user data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function saveUserData($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->userRepository->save($data);

        return $result;
    }

    /**
     * Update the user data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateUser($data, $id)
    {
        $id = $this->decrypt($id);
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $user = $this->userRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update the user data');
        }

        DB::commit();

        return $user;

    }    

    /**
     * Delete the user by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $id = $this->decrypt($id);
            $user = $this->userRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete the user data');
        }

        DB::commit();

        return $user;

    }

    public function getUsers($request){
        $data = array();
        $count_total = $count_filter = false;
        $search_for = false;
        if ($request->filled('search.value')) {
            $search_for = $request->input('search.value');
        }

        $buildQuery = $this->userRepository->getUser()->orderBy('users.id', 'desc');

        if ($search_for) {
            $buildQuery->where(function ($query) use ($search_for) {
                $query->where('users.name', 'LIKE', '%' . $search_for . '%');
                $query->orWhere('users.email', 'LIKE', '%' . $search_for . '%');
                //$query->orWhere('users.status', 'LIKE', '%' . $search_for . '%');
                $query->orWhere(DB::raw("DATE_FORMAT(users.created_at, '%b %d, %Y')"), 'LIKE', '%' . $search_for . '%');
            });
        }

        $start = 0;
        $length = 10;
        if ($request->filled('start')) {
            $start = $request->start;
        }
        if ($request->filled('length')) {
            $length = $request->length;
        }

        $query_total = $query = $buildQuery->get();
        if ($request->length != -1) {
            $query = $buildQuery->limit($length)->offset($start)->get();
        }
        $users = $query;

        if ($users->count() > 0) {

            foreach ($users as $key => $user) {
                $action = '';
                $eid = $this->encrypt($user->id);
                if(auth()->guard('web')->user()->id != $user->id){
                    $action .= '<a href="' . route('users.edit', ['user' => $eid]) . '" type="button" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-edit"></i></a>';
                    $action .= ' <button type="button" class="tw-modal-open text-red-500 hover:hover:text-red-700" title="Delete" data-toggle="modal" data-target="#delete_tw_modal" data-action="' . route('users.destroy', ['user' => $eid]) . '" data-msg="Are you sure you want to delete the user <strong>' . $user->name . '</strong>?"><i class="fa fa-fw fa-trash"></i></button>';
                }
                $data[$key][0] = $key + 1;
                $data[$key][1] = $user->name;
                $data[$key][2] = $user->email;
                $data[$key][3] = ($user->email) ? '<span class="label label-success">Enable</span>' : '<span class="label label-info">Disable</span>';
                $data[$key][4] = date('M d, Y', strtotime($user->created_at));
                $data[$key][5] = $action;
            }
        }

        $count_total = $query_total->count();
        $count_filter = $query->count();

        $dt_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($count_total),
            "recordsFiltered" => intval($count_filter),
            "data" => $data,
        );

        return $dt_data;
    }

}