<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userRepository->getAllPaginated();
        return view("users.index",compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ("users.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
{
$data = $request->validated();
$data["password"]=Hash::make($data["password"]);

 $this->userRepository->create($data);

return redirect()
->route("users.index")
->with("success","User created successfully");
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user= $this->userRepository->findById($id);
        return view("users.edit",compact("user"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request,$id)
    {
     $user = $this->userRepository->findById($id);
     $data = $request->validated();
     if(!empty($data["password"])){
        $data["password"]=Hash::make($data["password"]);
     }else{
        unset($data["password"]);
             };
        unset($data['password_confirmation']);

$this->userRepository->update($user, $data);
        return redirect()
        ->route("users.index")
        ->with("success","User updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
$user = $this->userRepository->findById($id);
$this->userRepository->delete($user);
        return redirect()->route("users.index")
        ->with("success","User deleted successfully");
    }
}
